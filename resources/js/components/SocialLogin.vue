<template>
  <a
    v-if="$page.props.is_social_login_enable"
    :href="route('social_login_google', parameters)"
    class="btn btn-light login-with-google-btn"
  >
    {{ __("Sign in with Google") }}</a
  >
</template>
  
  <script>
export default {
  props: ["is_checkout"],
  data: function () {
    return {
      parameters: this.is_checkout
        ? { checkout: 1, timezone: this.getBrowserTimezone() }
        : { timezone: this.getBrowserTimezone() },
    };
  },
};
</script>
  