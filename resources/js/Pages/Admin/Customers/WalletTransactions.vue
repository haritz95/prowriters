<template>
  <AppHead :title="data.title" />
  <ProfileLayout :customer="customer">
    <h5 class="mt-4 mb-4">{{ data.title }}</h5>
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
  </ProfileLayout>
</template>

<script>
import Table from "../../../components/Table.vue";
import ProfileLayout from "./Partials/ProfileLayout.vue";

export default {
  props: ["data", "transactions", "customer"],
  components: {
    Table,
    ProfileLayout,
  },
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
        ],
      },
    };
  },
};
</script>