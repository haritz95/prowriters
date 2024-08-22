<template>
  <AppHead :title="data.title" />
  <div class="container page-container">
    <PageTitle :title="data.title">
      <Link
        :href="route('customer.transactions.funds.create')"
        class="btn btn-sm btn-primary"
      >
        <i class="fa-solid fa-wallet"></i> {{ __("Add Funds") }}</Link
      >
    </PageTitle>
    <div class="row">
      <div class="col-md-12">
        <Table
          :options="tableOptions"
          :links="transactions.links"
          :total="transactions.total"
          :tableStyle="'fs-8 table-sm'"
        >
          <template v-slot>
            <tr v-for="(transaction, index) in transactions.data" :key="index">
              <td>{{ transaction.number }}</td>
              <td>{{ localDate(transaction.date) }}</td>
              <td>
                {{ transaction.type }}
              </td>
              <td>
                <div v-if="transaction.reference_link">
                  <div>{{ transaction.transactionable_type }}</div>
                  <Link :href="transaction.reference_link">{{
                    transaction.description
                  }}</Link>
                </div>
              </td>
              <td class="text-end">{{ formatMoney(transaction.amount) }}</td>
              <td class="text-end">{{ formatMoney(transaction.balance) }}</td>
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
  props: ["data", "transactions", "filters"],
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
            name: this.__("Type"),
            className: "",
          },
          {
            name: this.__("Description"),
            className: "",
          },
          {
            name: this.__("Amount"),
            className: "text-end",
          },
          {
            name: this.__("Balance"),
            className: "text-end",
          },
        ],
      },
    };
  },
};
</script>