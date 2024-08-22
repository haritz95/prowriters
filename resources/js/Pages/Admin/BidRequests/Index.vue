<template>
  <AppHead :title="data.title" />
  <div class="container page-container">
    <PageTitle :title="data.title" />

    <div class="row">
      <div class="col-md-3">
        <SearchBidRequest
          :data="data"
          :filters="filters"
          :only="['bid_requests', 'filters']"
        />
      </div>
      <div class="col-md-9">
        <Table
          :options="tableOptions"
          :links="bid_requests.links"
          :total="bid_requests.total"
          tableStyle="table-striped"
        >
          <template v-slot>
            <tr
              class="mb-2"
              v-for="(bid_request, index) in bid_requests.data"
              :key="index"
            >
              <td class="col-md-3">
                <Link
                  :href="route('admin.bidRequests.show', bid_request.uuid)"
                  >{{ bid_request.number }}</Link
                >
                <div>
                  <small class="text-muted">{{
                    bid_request.task.service.name
                  }}</small>
                </div>
              </td>
              <td class="col-md-2">
                {{ localDate(bid_request.created_at) }}
              </td>

              <td class="col-md-3">
                <Link
                  :href="route('admin.bidRequests.show', bid_request.uuid)"
                  >{{ bid_request.task.customer.full_name }}</Link
                >
              </td>

              <td class="col-md-2 text-center">
                <InlineTags :tags="[bid_request.status]"/>      
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
import SearchBidRequest from "./Partials/SearchBidRequests.vue";

export default {
  components: {
    Table,
    SearchBidRequest,
  },
  props: ["data", "bid_requests", "filters"],
  data() {
    return {
      tableOptions: {
        titles: [
          {
            name: this.__("Number"),
            className: "col-md-3",
          },
          {
            name: this.__("Posted"),
            className: "col-md-2",
          },
          {
            name: this.__("Customer"),
            className: "col-md-3",
          },
          {
            name: this.__("Status"),
            className: "col-md-2 text-center",
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
