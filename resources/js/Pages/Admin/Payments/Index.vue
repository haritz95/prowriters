<template>
  <AppHead :title="data.title" />
  <div class="container page-container">
    <div class="row">
      <div class="col-md-12">
        <PageTitle :title="data.title">
          <SearchBar
            :hide_inactive_search="true"
            :url="data.urls.search"
            :filters="filters.filters"
          />
        </PageTitle>

        <Table
          :options="tableOptions"
          :links="payments.links"
          :total="payments.total"
          :tableStyle="'fs-8'"
        >
          <template v-slot>
            <tr v-for="(payment, index) in payments.data" :key="index">
              <td><Link :href="route('admin.payments.show', payment.uuid)">{{ payment.number }}</Link></td>
              <td>{{ localDate(payment.date) }}</td>
              <td>
                <Link
                  :href="route('admin.customers.show', payment.from.uuid)"
                  >{{ payment.from.full_name }}</Link
                >
                <div><small class="text-muted">{{ payment.from.code }}</small></div>
              </td>
              <td>
                <div>{{ payment.method }}</div>
                <small class="text-muted">{{ __('Reference') }} {{ payment.reference }}</small>
              </td>              
              <td class="text-end">{{ formatMoney(payment.amount) }}</td> 
            </tr>
          </template>
        </Table>
      </div>
    </div>
  </div>
</template>

<script>
import Table from "../../../components/Table.vue";
import SearchBar from "../../../Shared/SearchBar.vue";
export default {
  components: {
    Table,
    SearchBar,
  },
  props: ["data", "payments", "filters"],
  data() {
    return {
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
            name: this.__("Method"),
            className: "",
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