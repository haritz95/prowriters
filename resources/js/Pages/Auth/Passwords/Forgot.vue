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
      <p class="text-muted mb-0" v-html="data.page.sub_title"></p>
      <hr />
      <div
        v-if="data.password_reset_link_sent"
        class="alert alert-success"
        role="alert"
      >
        {{ __("We have emailed your password reset link") }}
      </div>
      <form
        @submit.prevent="form.post(route('password.email'), formConfig)"
        autocomplete="off"
      >
        <div class="mb-3">
          <label class="form-label">{{ __("Email") }}</label>
          <input
            v-model="form.email"
            type="email"
            class="form-control shadow-none"
            required
            autocomplete="email"
            :placeholder="__('Email')"
            autofocus           
          />
          <ValidationError name="email" />
        </div>
        <div class="mb-3">
          <button
            :disabled="form.processing"
            type="submit"
            class="btn btn-primary btn-block"
          >
            {{ __("Send Link") }}
          </button>
        </div>
      </form>
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
      form: this.$inertia.form({
        email: null,
        password: null,
      }),
      formConfig: {
        onSuccess: () => this.form.reset(),
      },
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