<template>
  <AppHead :title="data.title" />
  <div class="container page-container">
    <PageTitle :title="data.title">
      <Link class="btn btn-sm btn-light" :href="route('admin.invoices.index')">
        <i class="fa-solid fa-arrow-left-long"></i> {{ __("Back to invoices") }}
      </Link>
    </PageTitle>

    <div class="row">
      <div class="col-md-9">
        <Invoice :data="data" :invoice="invoice" />
      </div>
      <div class="col-md-3">
        <div class="d-grid gap-2">
          <DialogLink
            v-if="parseFloat(invoice.amount_paid) < parseFloat(invoice.total)"
            :href="
              route('admin.invoices.adjust.from.wallet.create', invoice.uuid)
            "
            class="btn btn-success"
          >
            <i class="fa-solid fa-money-bill-wave"></i>
            {{ __("Adjust balance from Wallet") }}
          </DialogLink>
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