<template>
  <BidLayout :bid_request="data.bid_request" activeTab="bids">
    <div class="row" v-if="!data.is_hired">
      <div class="col-md-3">
        <Search
          :data="data"
          :filters="filters.filters"
          :only="['bids', 'filters']"
        />
      </div>

      <div class="col-md-9">        
        <div class="data-container">
          <Table
            :options="tableOptions"
            :links="bids.links"
            :total="bids.total"
            :text_no_record="__('The bids will appear here')"
          >
            <template v-slot>
              <tr class="mb-2" v-for="(bid, index) in bids.data" :key="index">
                <td class="col-md-2">
                  {{ localDate(bid.created_at) }}
                </td>
                <td class="col-md-2">
                  <img
                    class="avatar rounded-circle me-2"
                    :src="bid.author.small_avatar"
                    loading="lazy"
                  />
                  <Link
                    :href="
                      route('customer.bidRequests.author', [
                        data.bid_request.uuid,
                        bid.author.uuid,
                      ])
                    "
                    >{{ bid.author.code }}</Link
                  >
                </td>

                <td class="col-md-2 text-end">
                  {{ bid.duration_days }} {{ __('days') }}
                </td>
                <td class="col-md-2 text-end">
                  {{ bid.number_of_revisions }}
                </td>
                <td class="col-md-2 text-end">
                  {{ formatMoney(bid.total) }}
                </td>

                <td class="col-md-2 text-end">
                  <button
                    v-if="!data.bid_request.is_closed"
                    @click="accept(bid.uuid)"
                    type="button"
                    class="btn btn-sm btn-primary"
                  >
                    {{ __("Accept") }}
                  </button>
                </td>
              </tr>
            </template>
          </Table>
        </div>
      </div>
    </div>
    <div v-else>
      <div class="text-center text-success">{{ __('You have already hired for the task')}}</div>
      <div class="text-center">
        <p v-if="data.task_url">{{ __('Click') }} <Link :href="data.task_url">{{ __('here')  }}</Link> {{ __('to visit your task page')  }}</p>
      </div>
    </div>
  </BidLayout>
</template>

<script>
import BidLayout from "./Partials/BidLayout.vue";
import Table from "../../../components/Table.vue";
import Search from "./Partials/Search.vue";

export default {
  props: ["data", "bids", "filters"],
  components: {
    BidLayout,
    Table,
    Search,
  },
  data() {
    return {
      tableOptions: {
        titles: [
          {
            name: this.__("Date"),
            className: "col-md-2",
          },
          {
            name: this.__("From"),
            className: "col-md-2",
          },
          {
            name: this.__("Delivery"),
            className: "col-md-2 text-end",
          },
          {
            name: this.__("Revisions"),
            className: "col-md-2 text-end",
          },

          {
            name: this.__("Bid Amount"),
            className: "col-md-2 text-end",
          },
          {
            name: this.__("Action"),
            className: "col-md-2 text-end",
          },
        ],
      },
    };
  },
  methods: {
    accept(bidUUID) {
      let scope = this;
      this.confirmDialog(this.__("Accept the offer"), function () {
        scope.$inertia.post(
          route("customer.bidRequests.accept", [
            scope.data.bid_request.uuid,
            bidUUID,
          ])
        );
      });
    },
  },
};
</script>