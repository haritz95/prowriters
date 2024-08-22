<template>
  <AppHead :title="data.title" />
  <div class="container page-container">
    <PageTitle :title="data.title" />    
    <div class="row">
      <div class="col-md-12">
        <Table
          :options="tableOptions"
          :links="payments.links"
          :total="payments.total"
          :tableStyle="'fs-8'"
        >
          <template v-slot>
            <tr v-for="(payment, index) in payments.data" :key="index">
              <td>
                {{ payment.number }}
              </td>
              <td>
                {{ localDate(payment.created_at) }}
              </td>
              <td>{{ payment.method.name }}</td>
              <td>{{ payment.reference }}</td>
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

export default {
  components: {
    Table,
  },
  props: ["data", "payments"],
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
            name: this.__("Reference"),
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