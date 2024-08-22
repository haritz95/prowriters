<template>
  <AppHead :title="data.title" />
  <div class="container page-container">
    <PageTitle :title="data.title" />
    <div class="row">
      <div class="col-md-6">
        <h5>{{ data.subscription.title }}</h5>
        <div>{{ formatMoney(data.subscription.price) }}</div>
        
      </div>

      <div class="col-md-6">
        <form @submit.prevent="">
          <div class="mb-3">
            <label class="form-label" for="card-element">{{ __("Name") }}</label>
            <input
              id="card-holder-name"
              class="form-control"
              type="text"
              :placeholder="__('Name on the card')"
            />
          </div>
          <div class="mb-3">
            <label class="form-label" for="card-element">{{
              __("Credit Card")
            }}</label>
            <div id="card-element"></div>
            <div v-if="errorText" class="invalid-feedback d-block">{{ errorText }}</div>
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
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["data"],
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
      form: this.$inertia.form({
        name: null,
        token: null,
      }),
      formConfig: {
        onSuccess: () => this.form.reset(),
      },

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
    async onSubmit() {
      this.disableConfirmButton();
      this.loading = true;

      const { setupIntent, error } = await this.stripe.confirmCardSetup(
        this.data.client_secret,
        {
          payment_method: {
            card: this.cardElement,
            billing_details: {
              name: this.form.name,
            },
          },
        }
      );

      if (error) {
        this.enableConfirmButton();
        this.loading = false;
      } else {
        this.form.token = setupIntent.payment_method;
        this.$inertia.post(this.data.urls.submit_form, this.form);
      }
    },
  },
};
</script>
