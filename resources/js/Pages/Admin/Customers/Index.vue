<template>
  <AppHead :title="data.title" />
  <div class="container page-container">
    <PageTitle :title="data.title">
      <AddButton :href="route('admin.customers.create')" />
    </PageTitle>

    <div class="row">
      <div class="col-md-3">
        <Search
          :data="data"
          :filters="filters.filters"
          :only="['customers', 'filters']"
        />     
      </div>
      <div class="col-md-9">
        <Table
          :options="tableOptions"
          :links="customers.links"
          :total="customers.total"
          :only="['customers', 'filters']"
        >
          <template v-slot>
            <tr v-for="(customer, index) in customers.data" :key="index">
              <td class="align-middle">
                <img
                  :src="customer.small_avatar"
                  class="avatar rounded-circle"
                />
              </td>
              <td class="align-middle">
                <Link :href="route('admin.customers.show', customer.uuid)">{{
                  customer.full_name
                }}</Link>               

                <div v-if="customer.inactive">
                  <span class="badge text-bg-danger">{{ __("Inactive") }}</span>
                </div>
              </td>
              <td class="align-middle">
                <div>
                  <i class="fa-regular fa-envelope"></i> {{ customer.email }}
                </div>
                <div class="mt-2">
                  <i class="fa-solid fa-phone"></i> {{ customer.phone }}
                </div>
              </td>
              <td class="align-middle">
                {{ localDateTime(customer.last_login_at) }}
              </td>
            </tr>
          </template>
        </Table>
      </div>
    </div>
  </div>
</template>

<script>
import Search from "./Partials/Search.vue";
import Table from "../../../components/Table.vue";

export default {
  props: ["data", "customers", "filters"],
  components: {
    Table,
    Search,
  },
  data() {
    return {
      tableOptions: {
        titles: [
          {
            name: "",
            className: "",
          },
          {
            name: this.__("Name"),
            className: "",
          },
          {
            name: this.__("Email"),
            className: "",
          },
          {
            name: this.__("Last login"),
            className: "",
          },
        ],
      },
    };
  },
};
</script>