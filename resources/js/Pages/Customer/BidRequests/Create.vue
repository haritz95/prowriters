<template>
  <AppHead :title="data.title" />
  <div class="container page-container">
    <PageTitle :title="data.title">
      <Link :href="route('customer.bidRequests.services')" class="btn btn-sm btn-outline-secondary">
        <i class="fa-solid fa-arrow-left-long"></i> {{ __("Choose a different service") }} 
      </Link>
    </PageTitle>
    <div class="row">
      <div class="col-md-12">
        <BidContent
          v-if="data.service.service_type_id == data.service_types.content_writing"
          :data="data"
        />
        <BidAcademic
          v-if="data.service.service_type_id == data.service_types.academic_writing"
          :data="data"
        />
        <BidFixedPrice
          v-if="data.service.service_type_id == data.service_types.fixed_price"
          :data="data"
        />
      </div>
    </div>
  </div>
</template>

<script>
import BidFixedPrice from "../../../components/Order/Forms/BidFixedPrice.vue";
import BidAcademic from "../../../components/Order/Forms/BidAcademic.vue";
import BidContent from "../../../components/Order/Forms/BidContent.vue";

export default {
  components: {
    BidContent,
    BidAcademic,
    BidFixedPrice,
  },
  props: ["data"],
};
</script>
