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
          :links="transactions.links"
          :total="transactions.total"
          :tableStyle="'fs-8'"
        >
          <template v-slot>
            <tr v-for="(transaction, index) in transactions.data" :key="index">
              <td>
                <Link
                  :href="
                    route('admin.walletAdjustments.show', transaction.uuid)
                  "
                  >{{ transaction.number }}</Link
                >
              </td>
              <td>{{ transaction.type }}</td>
              <td>{{ localDate(transaction.created_at) }}</td>
              <td>
                <Link
                  :href="route('admin.customers.show', transaction.user.uuid)"
                  >{{ transaction.user.full_name }}</Link
                >
              </td>
              <td>
                <Link
                  :href="route('admin.users.show', transaction.user.uuid)"
                  >{{ transaction.adjuster.full_name }}</Link
                >
              </td>
              <td class="text-end">{{ formatMoney(transaction.amount) }}</td>
            </tr>
          </template>
        </Table>
      </div>
    </div>
  </div>
</template>

<script>
import Table from "../../../../components/Table.vue";
import SearchBar from "../../../../Shared/SearchBar.vue";
export default {
  components: {
    Table,
    SearchBar,
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
            name: this.__("Type"),
            className: "",
          },
          {
            name: this.__("Date"),
            className: "",
          },
          {
            name: this.__("Account"),
            className: "",
          },
          {
            name: this.__("Adjuster"),
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