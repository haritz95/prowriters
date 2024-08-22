<template>
  <AppHead :title="data.title" />
  <AccountLayout :author="author">
    <div class="card">
      <div class="card-header h5">
        {{ data.title }}
      </div>
      <div class="card-body">
        <form @submit.prevent="form.patch(data.urls.submit_form, formConfig)">
          <Input
            v-model="form.payment_method"
            :label="__('Preferred method for receiving payment')"
            name="payment_method"
            :required="true"
            :placeholder="__('e.g. Paypal, Stripe, etc.')"
          />
          <Input
            v-model="form.payment_method_details"
            :label="__('Payment method details')"
            name="payment_method_details"
            :required="true"
            :placeholder="__('e.g. Email for your Paypal account')"
          />

          <SubmitButton :disabled="form.disabled" />
        </form>
      </div>
    </div>
  </AccountLayout>
</template>

<script>
import AccountLayout from "./Partials/AccountLayout.vue";

import { Input, SubmitButton } from "../../../components/Form/Index.js";

export default {
  props: ["data", "author"],
  components: {
    AccountLayout,
    Input,
    SubmitButton,
  },
  data() {
    return {
      form: this.$inertia.form({
        payment_method: this.author.profile.payment_method,
        payment_method_details: this.author.profile.payment_method_details,
      }),
      formConfig: {
        preserveScroll: false,
      },
    };
  },
};
</script>