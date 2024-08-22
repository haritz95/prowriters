<template>
  <AppHead :title="data.title" />
  <AuthLayout :data="data.page.additional_data">
    <div class="p-5">
      <h6 class="h3">{{ data.title }}</h6>
      <hr />
      <form @submit.prevent="form.post(route('password.update'))" autocomplete="off" v-if="form.email && form.token">
        <div class="mb-3">
          <label class="form-label">{{ __("Password") }}</label>
          <div class="input-group">
            <span class="input-group-text" id="password"
              ><i class="fas fa-key"></i
            ></span>
            <input
              v-model="form.password"
              :type="password_visibility ? 'text' : 'password'"
              class="form-control shadow-none"
              required
            />
            <span         
              type="button"     
              class="input-group-text"
              @click="password_visibility = !password_visibility"
            >
              <i class="fa-regular fa-eye" v-if="password_visibility"></i>
              <i class="fa-regular fa-eye-slash" v-else></i>
            </span>
          </div>
          <ValidationError name="password" />
        </div>
        <div class="mb-3">
          <label class="form-label">{{ __("Confirm New Password") }}</label>
          <div class="input-group">
            <span class="input-group-text" id="password"
              ><i class="fas fa-key"></i
            ></span>
            <input
              v-model="form.password_confirmation"
              :type="password_confirm_visibility ? 'text' : 'password'"
              class="form-control shadow-none"
              required
              autocomplete="new-password"
            />
            <span
              type="button"
              class="input-group-text"
              @click="
                password_confirm_visibility = !password_confirm_visibility
              "
            >
              <i
                class="fa-regular fa-eye"
                v-if="password_confirm_visibility"
              ></i>
              <i class="fa-regular fa-eye-slash" v-else></i>
            </span>
          </div>
          <ValidationError name="password_confirmation" />
        </div>
        <div class="mb-3">
          <button
            :disabled="form.processing"
            type="submit"
            class="btn btn-primary btn-block"
          >
          {{ __('Reset Password') }}
          </button>
        </div>
      </form>
      <div v-else class="text-center"> {{ __('Not a valid password reset token') }}</div>
    </div>
  </AuthLayout>
</template>
  
  <script>
import AuthLayout from "../Partials/AuthLayout.vue";

export default {
  props: ["data"],
  components: {
    AuthLayout,
  },
  data() {
    return {
      password_visibility: false,
      password_confirm_visibility: false,
      form: this.$inertia.form({
        email: this.data.email,
        token: this.data.token,
        password: null,
        password_confirmation: null,
      }),
    };
  },
};
</script>
  
  <style>
.input-group-text {
  color: #aaa;
  background-color: #fff;
  border: 1px solid #aaa;
}
</style>