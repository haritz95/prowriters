<template>
  <AppHead :title="data.title" />
  <div class="container page-container">
    <PageTitle :title="data.title" />

    <div class="row">
      <div
        class="col-md-4 d-flex align-items-stretch text-center"
        v-for="(plan, index) in data.plans"
        :key="index"
      >
        <div class="card mb-4 rounded-3 shadow-sm w-100">
          <div class="card-header py-3">
            <h4 class="my-0 fw-normal">{{ plan.title }}</h4>
          </div>
          <div class="card-body d-flex flex-column">
            <h1 class="card-title h3">
              <span v-if="plan.price">{{ formatMoney(plan.price) }}</span>
              <span v-else>{{ formatMoney(0) }}</span>
              <small class="text-muted fw-light">/{{ __("month") }}</small>
            </h1>
            <div class="mb-4 nl2br" v-html="plan.description"></div>
            <div class="d-flex flex-row justify-content-center mt-auto">
              <Link
                :href="route('customer.subscriptions.create', plan.uuid)"
                class="w-100 btn btn-lg"
                v-bind:class="plan.is_free ? 'btn-outline-primary' : 'btn-primary'"
              >
                <span v-if="plan.is_free">{{ __("Sign up for free") }}</span>
                <span v-else>{{ __("Subscribe Now") }}</span>
              </Link>
            </div>
          </div>
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
