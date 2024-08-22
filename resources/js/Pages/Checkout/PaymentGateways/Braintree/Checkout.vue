<template>
  <CheckoutLayout
    :data="data"
    :title="__('Pay with') + ' ' + data.gateway_name"
    :is_payment_gateway_page="true"
  >
    <div id="dropin-wrapper">
      <div id="checkout-message"></div>
      <div id="dropin-container"></div>
      <div class="d-grid gap-2 mt-4" v-if="confirmButton && !form.processing">
        <button
          @click="confirmPayment"
          type="button"
          id="submit-button"
          class="btn btn-success"
        >
          <i class="fas fa-shopping-cart"></i> {{ __("Confirm Payment") }}
        </button>
      </div>
    </div>
    <div class="text-center" v-if="loading">{{ __("Please wait ") }} ...</div>
  </CheckoutLayout>
</template>

<script>
import CheckoutLayout from "../../CheckoutLayout.vue";
import dropin from "braintree-web-drop-in";

export default {
  props: ["data"],
  components: {
    CheckoutLayout,
  },
  mounted() {
    this.initializePaymentGateway();
  },

  data() {
    return {
      confirmButton: false,
      loading: true,
      errorText: null,
      dropinInstance: null,
      form: this.$inertia.form({
        payment_method_nonce: null,
      }),
    };
  },
  methods: {
    initializePaymentGateway() {
      let scope = this;

      let config = {
        authorization: scope.data.client_token,
        container: "#dropin-container",
      };

      if (scope.data.enable_paypal) {
        config.paypal = {
          flow: "vault",
        };
      }

      dropin.create(config, function (createErr, dropinInstance) {
        scope.hideLoading();
        scope.dropinInstance = dropinInstance;

        if (createErr) {
          scope.showError("Something went wrong, please try again later");
          return;
        }  

        dropinInstance.on("paymentMethodRequestable", function (event) {
          if (event.type == "PayPalAccount" && event.paymentMethodIsSelected == false) {
            dropinInstance.clearSelectedPaymentMethod();
            scope.hideConfirmButton();
          } else {
            scope.showConfirmButton();
          }
        });

        dropinInstance.on("noPaymentMethodRequestable", function () {
          scope.hideConfirmButton();
        });
      });
    },
    hideLoading() {
      this.loading = false;
    },
    showLoading() {
      this.loading = true;
    },
    hideConfirmButton() {
      this.confirmButton = false;
    },
    showConfirmButton() {
      this.confirmButton = true;
    },
    showError(message) {
      this.showAlertMessage(message);
    },
    confirmPayment() {
      this.hideConfirmButton();
      this.showLoading();
      let scope = this;

      this.dropinInstance.requestPaymentMethod(function (reqError, payload) {
        if (reqError) {
          scope.hideLoading();
          scope.showConfirmButton();
          scope.showError("Something went wrong, please try again later");
          return;
        }
        scope.form.payment_method_nonce = payload.nonce;
        scope.form.post(scope.data.urls.braintree_process);
      });
    },
  },
};
</script>
