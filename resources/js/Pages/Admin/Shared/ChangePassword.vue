<template>
  <Modal :title="data.title">
    <form @submit.prevent="form.patch(data.urls.submit_form, formConfig)">
      <Input
        v-model="form.password"
        :label="__('New Password')"
        name="password"
        type="password"
        :required="true"
      />     
      <SubmitButton :disabled="form.processing" />
    </form>
    <hr />
    <div class="h5">{{ __("Password rules") }}</div>
    <ul class="fs-8 text-muted">
      <li>{{ __("Require at least 8 characters") }}</li>
      <li>{{ __("Require at least one letter") }}</li>
      <li>{{ __("Require at least one number") }}</li>
      <li>{{ __("Require at least one symbol") }}</li>
      <li>
        {{ __("Require at least one uppercase and one lowercase letter") }}
      </li>
    </ul>
  </Modal>
</template>

<script>
import { Input, SubmitButton } from "../../../components/Form/Index.js";

export default {
  props: ["data"],
  components: {
    Input,
    SubmitButton,
  },
  data() {
    return {
      form: this.$inertia.form({
        current_password: null,
        password: null,
        password_confirmation: null,
      }),
      formConfig: {
        preserveScroll: false,
        onSuccess: () => this.form.reset(),
      },
    };
  },
};
</script>