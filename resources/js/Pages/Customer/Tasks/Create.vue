<template>
  <AppHead :title="data.title" />
  <div class="container page-container">
    <PageTitle :title="data.title">
      <Link :href="route('customer.tasks.create')" class="btn btn-sm btn-outline-primary me-2">
        <i class="fa-solid fa-arrow-left-long"></i> {{ __("Choose a different service") }} 
      </Link>
      <Link :href="route('checkout')" class="btn btn-sm btn-outline-secondary">
        {{ __("View Cart") }} <i class="fa-solid fa-arrow-right-long"></i>
      </Link>
    </PageTitle>
    <div class="row">
      <div class="col-md-12">        
        <Content
          v-if="data.service.service_type_id == data.service_types.content_writing"
          :data="data"
        />
        <Academic
          v-if="data.service.service_type_id == data.service_types.academic_writing"
          :data="data"
        />
        <FixedPrice
          v-if="data.service.service_type_id == data.service_types.fixed_price"
          :data="data"
        />
      </div>
    </div>
  </div>
</template>

<script>
import FixedPrice from "../../../components/Order/Forms/FixedPrice.vue";
import Academic from "../../../components/Order/Forms/Academic.vue";
import Content from "../../../components/Order/Forms/Content.vue";

export default {
  components: {
    Content,
    Academic,
    FixedPrice,
  },
  props: ["data"],
};
</script>
