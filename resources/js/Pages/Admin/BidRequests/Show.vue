<template>
  <BidLayout :bid_request="data.bid_request" activeTab="bids">
    <div class="row">
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
                <td class="col-md-3">
                  {{ localDate(bid.created_at) }}
                </td>
                <td class="col-md-3">
                  <Avatar :src="bid.author.small_avatar" />
                  <Link
                    :href="
                      route('admin.bidRequests.author', [
                        data.bid_request.uuid,
                        bid.author.uuid,
                      ])
                    "
                    >{{ bid.author.full_name }}</Link
                  >
                </td>

                <td class="col-md-3 text-end">
                  {{ formatMoney(bid.total) }}
                </td>

                <td class="text-end">
                  <div
                    v-if="
                      data.bid_request.task.author_id == bid.author.id
                    "
                  >
                    <i class="fa-solid fa-circle-check text-success"></i>
                  </div>
                </td>
              </tr>
            </template>
          </Table>
        </div>
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
            className: "col-md-3",
          },

          {
            name: this.__("Bid Amount"),
            className: "col-md-2 text-end",
          },
          {
            name: this.__("Winner"),
            className: "col-md-2 text-end",
          },
        ],
      },
    };
  },
};
</script>