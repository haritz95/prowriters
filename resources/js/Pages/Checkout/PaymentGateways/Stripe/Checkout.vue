<template>
  <CheckoutLayout
    :data="data"
    :title="__('Pay with') + ' ' + data.gateway_name"
    :is_payment_gateway_page="true"
  >
    <form id="payment-form">
      <div class="mb-3">
        <label class="form-label" for="card-element">{{ __("Credit or Debit Card") }}</label>
        <div id="card-element"></div>
        <div
          v-if="errorText"
          class="invalid-feedback d-block"
        >{{  errorText }}</div>
      </div>
      <button
        type="button"
        class="btn btn-success btn-block"
        :disabled="isButtonDisabled"
        @click="onSubmit"
      >
        <i class="fas fa-shopping-cart"></i> {{ __("Confirm Payment") }}
      </button>
    </form>
    <div class="text-center" v-if="loading">{{ __("Please wait ") }}...</div>
  </CheckoutLayout>
</template>

<script>
import CheckoutLayout from "../../CheckoutLayout.vue";

export default {
  props: ["data"],
  components: {
    CheckoutLayout,
  },
  mounted() {
    this.includeStripe(
      "js.stripe.com/v3",
      function () {        
        this.initializeStripe();
      }.bind(this)
    );
  },

  data() {
    return {     
      publishableKey: this.data.publishable_key,
      stripe: null,
      cardElement: null,
      isButtonDisabled: true,
      loading: false,  
      errorText: null,
      stripeStyle: {
        base: {
          color: "#32325d",
          fontFamily: '"Nunito", Helvetica, sans-serif',
          fontSmoothing: "antialiased",
          fontSize: "16px",
          "::placeholder": {
            color: "#aab7c4",
          },
        },
        invalid: {
          color: "#fa755a",
          iconColor: "#fa755a",
        },
      },
    };
  },
  methods: {
    includeStripe(URL, callback) {
      let documentTag = document,
        tag = "script",
        object = documentTag.createElement(tag),
        scriptTag = documentTag.getElementsByTagName(tag)[0];
      object.src = "//" + URL;
      if (callback) {
        object.addEventListener(
          "load",
          function (e) {
            callback(null, e);
          },
          false
        );
      }
      scriptTag.parentNode.insertBefore(object, scriptTag);
    },
    initializeStripe() {
      let scope = this;      
      scope.loading = false;      

      this.stripe = Stripe(this.publishableKey);

      var elements = this.stripe.elements();

      // Set up Stripe.js and Elements to use in checkout form

      this.cardElement = elements.create("card", {
        hidePostalCode: true,
        style: this.stripeStyle,
      });

      this.cardElement.mount("#card-element");

      scope.disableConfirmButton();    

      // Handle real-time validation errors from the card Element.
      this.cardElement.on("change", function (event) {
        if (event.complete) {
          scope.errorText = "";
          // enable payment button
          scope.enableConfirmButton();
        } else if (event.error) {
          // show validation to customer
          scope.errorText = event.error.message;
          scope.disableConfirmButton();
        } else {
          scope.errorText = "";
          scope.enableConfirmButton();
        }
      });
    },
    disableConfirmButton() {
      this.isButtonDisabled = true;
    },
    enableConfirmButton() {
      this.isButtonDisabled = false;
    },
    showError(message) {
      this.showAlertMessage(message);  
    },
    onSubmit() {
      this.disableConfirmButton();
      this.loading = true;
      this.stripe
        .createPaymentMethod({
          type: "card",
          card: this.cardElement,
        })
        .then(this.stripePaymentMethodHandler);
    },
    stripePaymentMethodHandler(result) {
      this.loading = false;
      if (result.error) {
        if (result.error.hasOwnProperty("message")) {
          this.showError(result.error.message);
        } else {
          this.showError(result.error);
        }

        this.enableConfirmButton();
      } else {
        let scope = this;
        //Otherwise send paymentMethod.id to your server
        scope.loading = true;

        axios
          .post(this.data.urls.stripe_process, {
            payment_method_id: result.paymentMethod.id,
          })
          .then(function (response) {
            scope.loading = false;
            scope.handleServerResponse(response.data);
          })
          .catch(function (error) {
            scope.loading = false;
            scope.enableConfirmButton();
          });
      }
    },
    handleServerResponse(response) {
      if (response.error) {
        // Show error from server on payment form
        this.showError(response.error);
        this.enableConfirmButton();
      } else if (response.requires_action) {
        // Use Stripe.js to handle required card action
        this.disableConfirmButton();
        this.stripe
          .handleCardAction(response.payment_intent_client_secret)
          .then(this.handleStripeJsResult);
      } else {
        // Show success message
        if (response.success) {
          this.disableConfirmButton();
          this.loading = true;
          this.$inertia.get(response.redirect_url);         
        }
      }
    },
    handleStripeJsResult(result) {
      if (result.error) {
        // Show error in payment form
        this.showError(result.error.message);
        this.enableConfirmButton();
      } else {
        // The card action has been handled
        // The PaymentIntent can be confirmed again on the server
        let scope = this;
        scope.disableConfirmButton();
        axios
          .post(this.data.urls.stripe_process, {
            payment_intent_id: result.paymentIntent.id,
          })
          .then(function (response) {
            scope.handleServerResponse(response.data);
          })
          .catch(function (error) {
            scope.showError(
              __("Could not process your payment. Please try again.")
            );
            scope.enableConfirmButton();
          });
      }
    },
  },
};
</script>