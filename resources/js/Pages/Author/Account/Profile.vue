<template>
  <AppHead :title="data.title" />
  <AccountLayout :author="author">
    <div class="card">
      <div class="card-header h5">
        {{ data.title }}
      </div>
      <div class="card-body">

        <h5 class="fs-7">{{ __("Author Level") }} : <span class="text-success">{{ data.author_level.name }}</span></h5>
        <hr />
        <h5 class="fs-7">{{ __("Services") }}</h5>
        <template v-for="(service, index) in data.services" :key="index">
          <span
            v-if="service"
            class="badge badge-lg badge-soft-primary me-2 mb-2"
            >{{ service.name }}</span
          >
        </template>

        
       
      </div>
    </div>
  </AccountLayout>
</template>
  
  <script>
import AccountLayout from "./Partials/AccountLayout.vue";

export default {
  props: ["data", "author"],
  components: {
    AccountLayout,
  },
};
</script>