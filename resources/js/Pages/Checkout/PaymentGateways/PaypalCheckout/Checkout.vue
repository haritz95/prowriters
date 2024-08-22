<template>
  <CheckoutLayout
    :data="data"
    :title="__('Pay with') + ' ' + data.gateway_name"
    :is_payment_gateway_page="true"
  >
    <div ref="paypalButton" id="paypal-button-container"></div>
    <div class="text-center" v-if="loading">{{ __("Please wait ") }}...</div>
  </CheckoutLayout>
</template>

<script>
import CheckoutLayout from "../../CheckoutLayout.vue";
import { loadScript } from "@paypal/paypal-js";

export default {
  props: ["data"],
  components: {
    CheckoutLayout,
  },
  async mounted() {
    let scope = this;

    let paypal;

    try {
      paypal = await loadScript({ "client-id": this.data.client_id });
    } catch (error) {
      console.error("failed to load the PayPal JS SDK script", error);
    }

    if (paypal) {
      try {
        await this.loadButtons(paypal);
      } catch (error) {
        console.error("failed to render the PayPal Buttons", error);
      }
    }
  },

  data() {
    return {
      loading: true,
      errorText: null,
    };
  },
  methods: {
    hideLoading() {
      this.loading = false;
      this.$refs.paypalButton.style.display = "block";
    },
    showLoading() {
      this.loading = true;
      this.$refs.paypalButton.style.display = "none";
    },

    loadButtons(paypal) {
      let scope = this;

      return paypal
        .Buttons({
          createOrder: function (data, actions) {
            return axios.post(scope.data.urls.generate_token).then(function (response) {
              if (response.data.status == "success") {
                return response.data.id;
              }
              return null;
            });
          },
          onApprove: function (data, actions) {
            if (data.orderID) {
              scope.$inertia.post(scope.data.urls.capture_payment, {
                order_id: data.orderID,
              });
              scope.showLoading();
            }
          },
          onDisplay: function () {
            scope.hideLoading();
          },
          onError: function (err) {
            scope.hideLoading();
            showError(
              scope.__(
                '"Something went wrong, please try again later, or use a different payment method"'
              )
            );
          },
        })
        .render("#paypal-button-container");
    },

    showError(message) {
      this.showAlertMessage(message);
    },
  },
};
</script>
