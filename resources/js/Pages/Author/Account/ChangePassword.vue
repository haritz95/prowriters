<template>
  <AppHead :title="data.title" />
  <AccountLayout :author="user">
    <div class="card">
      <div class="card-header h5">
        {{ data.title }}
      </div>
      <div class="card-body">
        <form @submit.prevent="form.patch(route('author.account.password.update'), formConfig)">
          <Input
            v-model="form.current_password"
            :label="__('Current Password')"
            name="current_password"
            type="password"
            :required="true"
          />
          <Input
            v-model="form.password"
            :label="__('New Password')"
            name="password"
            type="password"
            :required="true"
          />
          <Input
            v-model="form.password_confirmation"
            :label="__('Password Confirmation')"
            name="password_confirmation"
            type="password"
            :required="true"
          />
          <SubmitButton :disabled="form.disabled" />
        </form>
        <hr />
        <div class="h5">{{ __("Rules") }}</div>
        <ul class="fs-8 text-muted">
          <li>{{ __('Require at least 8 characters') }}</li>
          <li>{{ __('Require at least one letter') }}</li>
          <li>{{ __('Require at least one number') }}</li>
          <li>{{ __('Require at least one symbol') }}</li>
          <li>{{ __('Require at least one uppercase and one lowercase letter') }}</li>
        </ul>
      </div>
    </div>
  </AccountLayout>
</template>

<script>
import AccountLayout from "./Partials/AccountLayout.vue";

import { Input, SubmitButton } from "../../../components/Form/Index.js";

export default {
  props: ["data", "user"],
  components: {
    AccountLayout,
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