<template>
  <AppHead :title="data.title" />
  <div class="container page-container">
    <PageTitle :title="data.title">
      <Link
        :href="route('customer.bidRequests.services')"
        class="btn btn-primary btn-sm"
        ><i class="fa-solid fa-plus"></i> {{ __("New Bid Request") }}</Link
      >
    </PageTitle>
    <div class="row">
      <div class="col-md-12">
        <fieldset class="border rounded-3 p-3 mb-4">
          <legend
            class="float-none w-auto px-3 fs-6"
            style="margin-left: auto; margin-right: auto"
          >
            {{ __("How it works") }}
          </legend>

          <div class="row text-center">
            <div class="col-md-4 border-end mb-2 mb-md-0">
              <div class="text-primary">{{ __('Step 1') }}</div>
              <small class="text-muted">{{ __('Create new bid request to receive bids from writers') }}</small>
            </div>
            <div class="col-md-4 border-end mb-2 mb-md-0">
              <div class="text-primary">{{ __('Step 2') }}</div>
              <small class="text-muted">{{ __('Choose your writer and make a deposit') }}</small>
            </div>
            <div class="col-md-4 mb-2 mb-md-0">
              <div class="text-primary">{{ __('Step 3') }}</div>
              <small class="text-muted">{{ __('Check your paper & disburse payment') }}</small>
            </div>
          </div>
        </fieldset>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <Table
          :options="tableOptions"
          :links="bid_requests.links"
          :total="bid_requests.total"
        >
          <template v-slot>
            <tr
              class="mb-2"
              v-for="(bid_request, index) in bid_requests.data"
              :key="index"
            >
              <td class="col-md-4">
                <Link
                  :href="route('customer.bidRequests.show', bid_request.uuid)"
                  >{{ bid_request.number }}</Link
                >
                <div>
                  <small class="text-muted">{{
                    bid_request.task.service.name
                  }}</small>
                </div>
                <div>{{ bid_request.task.title }}</div>
              </td>
              <td class="col-md-2">
                {{ localDate(bid_request.created_at) }}
              </td>

              <td class="col-md-2">
                <InlineTags :tags="[bid_request.status]"/>                
              </td>
              <td class="col-md-2 text-end">
                {{ bid_request.bids_count }}              
              </td>
              <td class="col-md-2 text-end">
                {{ formatMoney(bid_request.budget) }}
              </td>
            </tr>
          </template>
        </Table>
      </div>
    </div>
  </div>
</template>

<script>
import Table from "../../../components/Table.vue";

export default {
  components: {
    Table,
  },
  props: ["data", "bid_requests", "filters"],
  data() {
    return {
      tableOptions: {
        titles: [
          {
            name: this.__("Details"),
            className: "col-md-4",
          },
          {
            name: this.__("Posted"),
            className: "col-md-2",
          },
          {
            name: this.__("Status"),
            className: "col-md-2",
          },
          {
            name: this.__("Bids"),
            className: "col-md-2 text-end",
          },
          {
            name: this.__("Your Budget"),
            className: "col-md-2 text-end",
          },
        ],
      },
    };
  },
};
</script>
