<template>
  <AppHead :title="data.title" />
  <div class="container page-container">
    <PageTitle :title="data.title" />
    <div class="row">
      <div class="col-md-3">
        <form @submit.prevent="form.get(data.urls.search)">
          <Select
            v-model="searchForm.status"
            :options="data.dropdowns.statuses"
            :label="__('Status')"
          />
          <Input
            v-model="searchForm.number"
            name="number"
            :label="__('Bill Number')"
          />
          <SearchButton @click="handleSearch" />
        </form>
      </div>

      <div class="col-md-9">
        <Table
          :options="tableOptions"
          :links="bills.links"
          :total="bills.total"
          :tableStyle="'fs-8'"
        >
          <template v-slot>
            <tr v-for="(bill, index) in bills.data" :key="index">
              <td>
                <Link :href="route('admin.bills.show', bill.uuid)">{{
                  bill.number
                }}</Link>
              </td>
              <td>{{ localDate(bill.created_at) }}</td>
              <td>
                <Link :href="route('admin.authors.show', bill.from.uuid)">{{
                  bill.from.full_name
                }}</Link>
              </td>
              <td class="text-center">
                <span v-if="bill.paid" class="badge bg-success">{{
                  __("Paid")
                }}</span>
                <span v-else class="badge bg-warning">{{ __("Unpaid") }}</span>
              </td>
              <td class="text-end">{{ formatMoney(bill.total) }}</td>
            </tr>
          </template>
        </Table>
      </div>
    </div>
  </div>
</template>

<script>
import Table from "../../../components/Table.vue";
import { Input, Select, SearchButton } from "../../../components/Form/Index.js";

export default {
  components: {
    Table,
    Input,
    Select,
    SearchButton,
  },
  props: ["data", "bills", "filters"],
  created() {
    if (this.filters?.search) {
      var search = this.filters.search;

      if (search?.status) {
        this.searchForm.status = parseInt(search.status);
      }

      if (search?.number) {
        this.searchForm.number = parseInt(search.number);
      }
    }
  },
  methods: {
    handleSearch() {
      this.$inertia.get(
        this.data.urls.search,
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
            name: this.__("Date"),
            className: "",
          },
          {
            name: this.__("From"),
            className: "",
          },
          {
            name: this.__("Status"),
            className: "text-center",
          },
          {
            name: this.__("Amount"),
            className: "text-end",
          },
        ],
      },
    };
  },
};
</script>