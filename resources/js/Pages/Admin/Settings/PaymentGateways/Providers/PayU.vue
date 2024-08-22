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
      v-model="form.keys.merchant_key"
      name="keys.merchant_key"
      :label="__('Merchant Key')"
      :required="true"
    />
    <Input
      v-model="form.keys.merchant_salt"
      name="keys.merchant_salt"
      :label="__('Merchant Salt')"
      :required="true"
    />
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
          merchant_key: null,
          merchant_salt: null,
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