<template>
  <BusinessLayout :title="data.title">
    <TitleBar :title="data.title" :create_link="route('admin.additionalServices.create')"/>
    <SearchBar :url="route('admin.additionalServices.index')" :filters="filters.filters" :hide_inactive_search="true" />
        
    <Table :options="tableOptions" :links="additional_services.links" :total="additional_services.total">
      <template v-slot>
        <tr v-for="(additionalService, index) in additional_services.data" :key="index">
          <td>
            <Link :href="route('admin.additionalServices.edit', additionalService.slug)">{{ additionalService.name }}</Link>
          </td>               
          <td>
            {{ data.price_types[additionalService.type]}}
          </td>               
          <td class="text-end">
            {{ formatMoney(additionalService.price) }}
          </td>             
                      
          <td class="col-md-2 text-end">
            <DestroyButton :delete_url="route('admin.additionalServices.destroy', additionalService.slug)"/>
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
  props: ["data", "additional_services", "filters"],
  data() {
    return {
      tableOptions: {
        titles: [
          {
            name: this.__("Name"),
            className: "col-md-5",
          },          
          {
            name: this.__("Price Type"),
            className: "col-md-2",
          },          
          {
            name: this.__("Price"),
            className: "col-md-3 text-end",
          },          
          {
            name: this.__("Action"),
            className: "col-md-2 text-end",
          },
        ],
      },
    };
  },
};
</script>