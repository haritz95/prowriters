<template>
  <AppHead :title="data.title" />
  <div class="container page-container">
    <PageTitle :title="data.title" />

    <div class="row">
      <div class="col-md-12" v-if="data.applied_bids.length > 0">
        <fieldset class="border rounded-3 p-3 mb-4">
          <legend class="float-none w-auto px-3 fs-8 text-success">
            {{ __("Applied") }}
          </legend>
          <Table
            :options="appliedBidTableOptions"
            :tableStyle="'table-striped'"
          >
            <template v-slot>
              <tr class="mb-2" v-for="(bid, index) in data.applied_bids" :key="index">
                <td class="col-md-3">
                  <Link :href="route('author.bidRequests.show', bid.bid_request.uuid)">{{
                    bid.bid_request.number
                  }}</Link>
                </td>
                <td class="col-md-2">
                  {{ localDate(bid.created_at) }}
                </td>

                <td class="col-md-2 text-end">
                  {{ formatMoney(bid.total) }}
                </td>
                <td class="col-md-2 text-end">
                  {{ formatMoney(bid.author_payment_amount) }}
                </td>
              </tr>
            </template>
          </Table>
        </fieldset>
      </div>
      <div class="col-md-12">
        <div class="h5 bg-light p-2">
          {{ __("Tasks available for bidding") }}
        </div>
        <hr />
      </div>
      <div class="col-md-3">
        <Search
          :data="data"
          :filters="filters.filters"
          :only="['bid_requests', 'filters']"
        />
      </div>
      <div class="col-md-9">
        <div class="data-container">
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
                <td class="col-md-3">
                  <Link :href="route('author.bidRequests.show', bid_request.uuid)">{{
                    bid_request.number
                  }}</Link>
                  <div>
                    {{ bid_request.task.service.name }}
                  </div>
                </td>
                <td class="col-md-2">
                  {{ localDate(bid_request.created_at) }}
                </td>

                <td class="col-md-3">
                  <span v-if="bid_request.is_closed" class="badge text-bg-danger">{{
                    __("Closed")
                  }}</span>
                  <span v-else class="badge text-bg-success">{{ __("Open") }}</span>
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
  </div>
</template>

<script>
import Table from "../../../components/Table.vue";
import Search from "./Partials/Search.vue";
export default {
  components: {
    Table,
    Search,
  },
  props: ["data", "bid_requests", "filters"],
  data() {
    return {
      appliedBidTableOptions: {
        titles: [
          {
            name: this.__("Bid Request Number"),
            className: "col-md-3",
          },
          {
            name: this.__("Applied at"),
            className: "col-md-3",
          },
          {
            name: this.__("Bidding Amount"),
            className: "col-md-2  text-end",
          },
          {
            name: this.__("Earning"),
            className: "col-md-2 text-end",
          },
        ],
      },
      tableOptions: {
        titles: [
          {
            name: this.__("Details"),
            className: "col-md-3",
          },
          {
            name: this.__("Posted"),
            className: "col-md-3",
          },
          {
            name: this.__("Status"),
            className: "col-md-2",
          },
          {
            name: this.__("Budget"),
            className: "col-md-2 text-end",
          },
        ],
      },
    };
  },
};
</script>
