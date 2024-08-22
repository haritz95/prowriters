<template>
  <form @submit.prevent="form.patch(data.urls.submit_form)">
    <Input
      v-model="form.name"
      name="name"
      :label="__('Display Name')"
      :required="true"
    />
    <Input
      v-model="form.keys.public_key"
      name="keys.public_key"
      :label="__('Public Key')"
      :required="true"
    />
    <Input
      v-model="form.keys.secret_key"
      name="keys.secret_key"
      :label="__('Secret Key')"
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
  Input,
  CheckBox,
  SubmitButton,
} from "../../../../../components/Form/Index.js";

export default {
  components: {  
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
          public_key: null,
          secret_key: null,
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