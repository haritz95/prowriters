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
              <td>{{ transaction.number }}</td>
              <td>{{ localDate(transaction.date) }}</td>
              <td>
                <Link :href="transaction.user_profile_link">{{
                  transaction.user
                }}</Link>
              </td>
              <td>{{ transaction.type }}</td>              
              <td>
                <template v-if="transaction.reference_link">
                  <div>{{ transaction.transactionable_type }}</div>
                  <Link :href="transaction.reference_link">{{
                    transaction.description
                  }}</Link>
                </template>
                <template v-else>
                  {{ transaction.reference }}
                </template>
              </td>
              <td class="text-end">{{ transaction.amount }}</td>
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
            name: this.__("User"),
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
        ],
      },
    };
  },
};
</script>