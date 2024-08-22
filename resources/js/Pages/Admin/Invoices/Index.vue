<template>
  <AppHead :title="data.title" />
  <div class="container page-container">
    <PageTitle :title="data.title">
      <AddButton :href="route('admin.invoices.create')" />
    </PageTitle>

    <div class="row">
      <div class="col-md-3">
        <Search :data="data" :filters="filters"/>
      </div>
      <div class="col-md-9">
        <Table
          :options="tableOptions"
          :links="invoices.links"
          :total="invoices.total"
        >
          <template v-slot>
            <tr v-for="(invoice, index) in invoices.data" :key="index">
              <td>
                <Link :href="route('admin.invoices.show', invoice.uuid)">{{
                  invoice.number
                }}</Link>
              </td>
              <td class="">
                {{ localDate(invoice.invoice_date) }}
              </td>
              <td class="">
                <Link :href="route('admin.customers.show', invoice.customer.uuid)">{{ invoice.customer.full_name }}</Link>
              </td>
              <td>
                <InlineTags :tags="[invoice.status]"/>                
              </td>
              <td class="text-end">
                {{ formatMoney(invoice.total) }}
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
import Search from "./Partials/Search.vue";

export default {
  props: ["data", "invoices", "filters"],
  components: {
    Table,
    Search,
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
            name: this.__("Customer"),
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