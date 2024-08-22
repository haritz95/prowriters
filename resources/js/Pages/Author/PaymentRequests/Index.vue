<template>
  <AppHead :title="data.title" />
  <div class="container page-container">
    <PageTitle :title="data.title" />

    <div class="row">
      <div class="col-md-3">
        <div class="fs-8" id="search-form">
          <form>
            <Select
              v-model="searchForm.status"
              :options="data.dropdowns.statuses"
              :label="__('Status')"
            />
            <Input
              v-model="searchForm.number"
              name="number"
              :label="__('Number')"
            />

            <CheckBox
              v-model="searchForm.show_archived"
              name="show_archived"
              :label="__('Show Archived')"
            />
            <SearchButton @click="handleSearch" />
          </form>
        </div>
      </div>

      <div class="col-md-9">
        <Table
          :options="tableOptions"
          :links="payment_requests.links"
          :total="payment_requests.total"
        >
          <template v-slot>
            <tr
              v-for="(paymentRequest, index) in payment_requests.data"
              :key="index"
            >
              <td>
                <Link
                  :href="
                    route(
                      'author.paymentRequests.show',
                      paymentRequest.uuid
                    )
                  "
                  >{{ paymentRequest.number }}</Link
                >
              </td>
              <td>
                {{ localDate(paymentRequest.created_at) }}
              </td>
              <td>
                <span v-if="paymentRequest.paid" class="badge bg-success">{{
                  __("Paid")
                }}</span>
                <span v-else class="badge bg-danger">{{ __("Unpaid") }}</span>

                <span
                  v-if="paymentRequest.is_archived_for_author"
                  class="ms-2 badge bg-secondary"
                >
                  {{ __("Archived") }}
              </span>
                
              </td>
              <td class="text-end">
                {{ formatMoney(paymentRequest.total) }}
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
import {
  Input,
  Select,
  CheckBox,
  SearchButton,
} from "../../../components/Form/Index.js";

export default {
  props: ["data", "payment_requests", "filters"],
  components: {
    Table,
    Input,
    Select,
    CheckBox,
    SearchButton,
  },
  created() {
    if (this.filters?.search) {
      var search = this.filters.search;

      if (search?.status) {
        this.searchForm.status = parseInt(search.status);
      }

      if (search?.number) {
        this.searchForm.number = parseInt(search.number);
      }

      if (search?.show_archived) {
        this.searchForm.show_archived = parseInt(search.show_archived);
      }
    }
  },
  methods: {
    handleSearch() {
      this.$inertia.get(
        route("author.paymentRequests.index"),
        { search: this.searchForm },
        {
          preserveState: true,
          replace: true,
        }
      );
    },
  },
  data() {
    return {
      searchForm: {
        status: "",
        number: "",
      },
      tableOptions: {
        titles: [
          {
            name: this.__("Number"),
            className: "",
          },
          {
            name: this.__("Created at"),
            className: "",
          },
          {
            name: this.__("Status"),
            className: "",
          },
          {
            name: this.__("Total"),
            className: "text-end",
          },
        ],
      },
    };
  },
};
</script>