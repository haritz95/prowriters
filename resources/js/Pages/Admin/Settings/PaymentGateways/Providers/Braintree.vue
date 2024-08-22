<template>
  <form @submit.prevent="form.patch(data.urls.submit_form)">
    <Select
      :options="data.dropdowns.environments"
      v-model="form.keys.environment"
      name="keys.environment"
      :label="__('Environment')"
      :required="true"
    />
    <Input
      v-model="form.name"
      name="name"
      :label="__('Display Name')"
      :required="true"
    />
    <Input
      v-model="form.keys.merchant_id"
      name="keys.merchant_id"
      :label="__('Merchant Id')"
      :required="true"
    />
    <Input
      v-model="form.keys.public_key"
      name="keys.public_key"
      :label="__('Public Key')"
      :required="true"
    />
    <Input
      v-model="form.keys.private_key"
      name="keys.private_key"
      :label="__('Private Key')"
      :required="true"
    />
    <CheckBox v-model="form.keys.is_paypal_enabled" name="keys.is_paypal_enabled" :label="__('Enable Paypal')" />
    <CheckBox v-model="form.inactive" name="inactive" :label="__('Inactive')" />
    <div class="mb-3">
      <SubmitButton :disabled="form.processing" />
    </div>
  </form>
</template>

<script>
import {
  Select,
  Input,
  CheckBox,
  SubmitButton,
} from "../../../../../components/Form/Index.js";

export default {
  components: {
    Select,
    Input,
    CheckBox,
    SubmitButton,
  },
  inject: ["data"],
  data() {
    return {
      form: this.$inertia.form(this.prepareForm()),
    };
  },
  methods: {
    prepareForm() {
      let inputs = {
        name: null,
        keys: {
          environment: null,
          merchant_id: null,
          public_key: null,
          private_key: null,
          is_paypal_enabled: null,
        },
        inactive: null,
      };
      if (this.data.settings) {
        inputs = { ...inputs, ...this.data.settings };
      }
      return inputs;
    },
  },
};
</script>