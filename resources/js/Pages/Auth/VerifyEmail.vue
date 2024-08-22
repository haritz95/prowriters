<template>
    <AppHead :title="data.title" />
    <AuthLayout :data="data.page.additional_data">
      <div class="p-5">
        <h3>{{ __('Verify Your Email Address') }}</h3>
        <hr>
        <div>
            <div v-if="data.resent" class="alert alert-success" role="alert">
              {{ __('A fresh verification link has been sent to your email address.') }}
            </div>
            {{ __('Before proceeding, please check your email for a verification link.') }}
            {{ __('If you did not receive the email') }},
            <Link class="btn btn-link p-0 m-0 align-baseline" :href="route('verification.resend')" method="post" as="button" type="button">
            {{ __('click here to request another') }}</Link>
        </div>
      </div>
</AuthLayout>
 
  </template>
  
  <script>
  import Layout from "../../Shared/UnAuthenticatedLayout.vue";
  import AuthLayout from "./Partials/AuthLayout.vue";

  export default {   
    props: ["data"],
    components: {
    AuthLayout,
  },
    layout: Layout,
  };
  </script>
  