<template>
  <ProfileLayout :customer="customer">
    <h5 class="mt-4 mb-4">{{ data.title }}</h5> 
    <Table
      :options="tableOptions"
      :links="payments.links"
      :total="payments.total"
      :tableStyle="'fs-8'"
    >
      <template v-slot>
        <tr v-for="(payment, index) in payments.data" :key="index">
          <td><Link :href="route('admin.payments.show', payment.uuid)">{{ payment.number }}</Link></td>
          <td>{{ localDate(payment.created_at) }}</td>
          <td>{{ payment.method }}</td>
          <td class="text-end">{{ formatMoney(payment.amount) }}</td>
        </tr>
      </template>
    </Table>
  </ProfileLayout>
</template>

<script>
import Table from "../../../components/Table.vue";
import ProfileLayout from "./Partials/ProfileLayout.vue";

export default {
  props: ["data", "payments", "customer"],
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