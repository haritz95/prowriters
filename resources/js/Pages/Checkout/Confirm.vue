<template>
  <AppHead :title="data.title" />
  <div class="container page-container">
    <div class="row">
      <div class="col-md-12">
        <h3>{{ data.title }}</h3>
        <hr />
        <div class="text-center">
          <h2>
            <i class="fas fa-check-circle text-success"></i>
            {{ __("Thank you for your order") }}!
          </h2>
          <div class="mt-2 mb-4">
            {{ __("Check your email for your receipt") }}            
          </div>
          <p v-if="data.task_url">{{ __('Click') }} <Link :href="data.task_url">{{ __('here')  }}</Link> {{ __('to visit your task page')  }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["data"],
};
</script>