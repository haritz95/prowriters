<template>
  <AppHead :title="data.title" />
  <div class="container page-container">
    <PageTitle :title="data.title" v-if="data.is_user_logged_in">
      <Link
        class="btn btn-sm btn-light"
        :href="route('customer.invoices.index')"
      >
        <i class="fa-solid fa-arrow-left-long"></i> {{ __("Back to invoices") }}
      </Link>
    </PageTitle>
    <div class="row">
      <div class="col-md-9">
        <Invoice :data="data" :invoice="invoice" />
      </div>

      <div class="col-md-3">
        <div class="d-grid gap-2">
          <Link
            v-if="data.allow_pay_now"
            class="btn btn-sm btn-success mb-4"
            :href="route('customer.invoices.pay', invoice.uuid)"
          >
            <i class="far fa-check-circle"></i> {{ __("Pay Now") }}
          </Link>

          <a
            class="btn btn-sm btn-outline-secondary"
            :href="route('public.invoices.download', invoice.uuid)"
          >
            <i class="fa-solid fa-cloud-arrow-down"></i> {{ __("Download") }}
          </a>
        </div>
      </div>
    </div>
  </div>
</template>
    
<script>
import Invoice from "../../../components/Invoice.vue";
export default {
  props: ["data", "invoice"],
  components: {
    Invoice,
  },
};
</script>