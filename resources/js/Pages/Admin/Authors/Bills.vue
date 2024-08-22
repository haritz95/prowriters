<template>
  <AppHead :title="data.title" />
  <ProfileLayout :author="author">
    <h5 class="mt-4 mb-4">{{ data.title }}</h5>     
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
  </ProfileLayout>
</template>

<script>
import Table from "../../../components/Table.vue";
import ProfileLayout from "./Partials/ProfileLayout.vue";
export default {
  props: ["data", "author", "bills"],
  components: {
    ProfileLayout,
    Table,
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