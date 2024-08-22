<template>
  <BusinessLayout :title="data.title">
    <TitleBar :title="data.title" :create_link="route('admin.subjects.create')"/>
    <SearchBar :url="route('admin.subjects.index')" :filters="filters.filters" :hide_inactive_search="true" />
        
    <Table :options="tableOptions" :links="subjects.links" :total="subjects.total">
      <template v-slot>
        <tr v-for="(subject, index) in subjects.data" :key="index">
          <td>
            <Link :href="route('admin.subjects.edit', subject.slug)">{{ subject.name }}</Link>
          </td>   
          <td class="text-end">
            {{ (subject.percentage) ? parseFloat(subject.percentage) : 0}}%
          </td>              
          <td class="col-md-2 text-end">
            <DestroyButton :delete_url="route('admin.subjects.destroy', subject.id)"/>
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
  props: ["data", "subjects", "filters"],
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