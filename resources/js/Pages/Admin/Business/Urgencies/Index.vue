<template>
  <BusinessLayout :title="data.title">

    <TitleBar :title="data.title" :create_link="route('admin.urgencies.create')"/>
    <SearchBar :url="route('admin.urgencies.index')" :filters="filters.filters" :hide_inactive_search="true" />
        
    <Table :options="tableOptions" :links="urgencies.links" :total="urgencies.total">
      <template v-slot>
        <tr v-for="(urgency, index) in urgencies.data" :key="index">
          <td>
            <Link :href="route('admin.urgencies.edit', urgency.id )">{{ urgency.name }}</Link>
          </td>               
          <td class="text-end">
            {{ (urgency.percentage) ? parseFloat(urgency.percentage) : 0}}%
          </td>               
          <td class="col-md-2 text-end">
            <DestroyButton :delete_url="route('admin.urgencies.destroy', urgency.id)"/>
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
  props: ["data", "urgencies", "filters"],
  data() {
    return {
      tableOptions: {
        titles: [
          {
            name: this.__("Name"),
            className: "col-md-6",
          },          
          {
            name: this.__("Markup"),
            className: "col-md-4 text-end",
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