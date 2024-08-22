<template>
  <ul class="nav flex-column">
    <Link class="nav-link" :href="route('admin.settings.general')">{{
      __("General")
    }}</Link>
    <Link class="nav-link" :href="route('admin.settings.service')">{{
      __("Service")
    }}</Link>
    <Link class="nav-link" :href="route('admin.settings.currency')">{{
      __("Currency")
    }}</Link>

    <Link class="nav-link" :href="route('admin.settings.social.login')">{{
      __("Social Login")
    }}</Link>
    <Link class="nav-link" :href="route('admin.settings.recaptcha')">{{
      __("Google reCAPTCHA")
    }}</Link>

    <li class="nav-item dropend">
      <a
        class="nav-link dropdown-toggle"
        data-bs-toggle="dropdown"
        href="#"
        role="button"
        aria-expanded="false"
        >{{ __("Email") }}</a
      >
      <ul class="dropdown-menu">
        <li>
          <Link class="dropdown-item" :href="route('admin.settings.email')">{{
            __("Configuration")
          }}</Link>
        </li>
        <li>
          <Link
            class="dropdown-item"
            :href="route('admin.settings.email.test')"
            >{{ __("Send Test Email") }}</Link
          >
        </li>
      </ul>
    </li>

    <Link class="nav-link" :href="route('admin.settings.appSettings')">{{
      __("Application")
    }}</Link>
    <Link class="nav-link" :href="route('admin.settings.websiteUi')">{{
      __("Website UI")
    }}</Link>
    <Link class="nav-link" :href="route('admin.settings.social.links')">{{
      __("Social Media Links")
    }}</Link>
    <Link
      class="nav-link"
      :href="route('admin.settings.systemLanguages.index')"
      >{{ __("Languages") }}</Link
    >
    <Link class="nav-link" :href="route('admin.settings.payment.gateways')">{{
      __("Payments Gateways")
    }}</Link>
    <!-- <Link class="nav-link" :href="route('admin.settings.subscriptionPlans.index')">{{
      __("Subscription Plans")
    }}</Link> -->
    <Link
      class="nav-link"
      :href="route('admin.settings.offline.payment.methods.index')"
      >{{ __("Offline Payment Methods") }}</Link
    >
    <Link class="nav-link" :href="route('admin.settings.googleTagManager')">{{
      __("Google Tag Manager")
    }}</Link>
    <Link class="nav-link" :href="route('admin.settings.cache')">{{
      __("Clear Cache")
    }}</Link>
  </ul>
</template>

<script>
export default {};
</script>