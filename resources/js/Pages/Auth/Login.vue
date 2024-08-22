<template>
  <AppHead :title="data.page.additional_data.meta_title">
    <meta
      name="description"
      :content="data.page.additional_data.meta_description"
    />
    <meta name="keywords" :content="data.page.additional_data.meta_keywords" />
    <meta property="og:title" :content="data.page.additional_data.meta_title" />
    <meta
      property="og:image"
      :content="asset(data.page.additional_data.meta_image)"
    />
  </AppHead>

  <AuthLayout :data="data.page.additional_data">
    <div class="p-5">
      <h6 class="h3">{{ data.page.title }}</h6>

      <hr />
      <SocialLogin />
      <form class="mt-4" @submit.prevent="form.post(route('login'))" autocomplete="off">
        <div class="mb-3">
          <label class="form-label">{{ __("Email") }}</label>
          <div class="input-group">
            <span class="input-group-text" id="email"
              ><i class="far fa-user"></i
            ></span>
            <input
              v-model="form.email"
              type="email"
              class="form-control shadow-none"
              required
              autocomplete="email"
              :placeholder="__('Email')"
              autofocus
            />
          </div>
          <ValidationError name="email" />
        </div>
        <div class="mb-3">
          <label class="form-label">{{ __("Password") }}</label>
          <div class="input-group">
            <span class="input-group-text" id="password"
              ><i class="fas fa-key"></i
            ></span>
            <input
              v-model="form.password"
              type="password"
              class="form-control shadow-none"
              :placeholder="__('Password')"
              required
            />
          </div>
          <ValidationError name="password" />
        </div>
        <ValidationError name="recaptcha" />
        <div class="mb-3">
          <button
            :disabled="form.processing || !form.recaptcha"
            type="submit"
            class="btn btn-primary btn-block"
          >
            {{ __("Login") }}
          </button>
        </div>
      </form>
      <div class="text-muted mt-5">
        {{ __("Don't have an account ?") }}
        <Link :href="route('register')">{{ __("Register") }}</Link>
        <div>
          <Link class="small" :href="route('password.request')">
            {{ __("Forgot password") }}?
          </Link>
        </div>
      </div>
    </div>
  </AuthLayout>
</template>

<script>
import AuthLayout from "./Partials/AuthLayout.vue";
import { useGoogleCaptcha } from "../../composable/Recaptcha.js";
import SocialLogin from "../../components/SocialLogin.vue";

export default {
  props: ["data"],
  components: {
    AuthLayout,
    SocialLogin,
  }, 
  data() {
    return {
      form: this.$inertia.form({
        email: null,
        password: null,
        recaptcha: (window.recaptcha.is_enabled) ? useGoogleCaptcha().token : true,
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