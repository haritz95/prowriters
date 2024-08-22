<template>
  <BusinessLayout :title="data.title">

    <TitleBar :title="data.title" :create_link="route('admin.serviceLevels.create')"/>
    <SearchBar :url="route('admin.serviceLevels.index')" :filters="filters.filters" :hide_inactive_search="true" />
        
    <Table :options="tableOptions" :links="service_levels.links" :total="service_levels.total">
      <template v-slot>
        <tr v-for="(serviceLevel, index) in service_levels.data" :key="index">
          <td>
            <Link :href="route('admin.serviceLevels.edit', serviceLevel.id)">{{ serviceLevel.name }}</Link>
          </td>
          <td class="text-center">
            <span v-if="serviceLevel.is_default"><i class="fa-solid fa-circle-check text-success"></i></span>
          </td>   
          <td class="text-end">
              {{ formatMoney((serviceLevel.price) ? parseFloat(serviceLevel.price) : 0) }}
          </td>            
          <td class="col-md-2 text-end">
            <DestroyButton :delete_url="route('admin.serviceLevels.destroy', serviceLevel.id)"/>
          </td>
        </tr>
      </template>
    </Table>
  </BusinessLayout>
</template>

<script>
import BusinessLayout from "../Partials/BusinessLayout.vue";
import SearchBar from "../../../../Shared/SearchBar.vue";
import TitleBar from "../Partials/TitleBar.vue";
import DestroyButton from "../../../../components/Form/DestroyButton.vue";

import Table from "../../../../components/Table.vue";

export default {
  components: {
    BusinessLayout,
    SearchBar,
    Table,
    TitleBar,
    DestroyButton,
  },
  props: ["data", "service_levels", "filters"],
  data() {
    return {
      tableOptions: {
        titles: [
          {
            name: this.__("Name"),
            className: "col-md-4",
          },          
          {
            name: this.__("Default"),
            className: "col-md-2 text-center",
          },          
          {
            name: this.__("Price"),
            className: "col-md-3 text-end",
          },          
          {
            name: this.__("Action"),
            className: "col-md-1 text-end",
          },
        ],
      },
    };
  },
};
</script>