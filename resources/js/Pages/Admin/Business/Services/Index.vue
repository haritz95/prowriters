<template>
  <BusinessLayout :title="data.title">    
    <TitleBar :title="data.title" :create_link="route('admin.services.create')" />
    <SearchBar
      :url="route('admin.services.index')"
      :filters="filters.filters"
      :hide_inactive_search="true"
    />

    <Table :options="tableOptions" :links="services.links" :total="services.total">
      <template v-slot>
        <tr v-for="(service, index) in services.data" :key="index">
          <td>
            <Link :href="route('admin.services.edit', service.slug)">{{
              service.name
            }}</Link>
          </td>

          <td class="col-md-2 text-end">
            <Link
              class="btn btn-sm btn-light me-2"
              :href="route('admin.services.configurationHome', service.slug)"
            >
              <i class="fa-solid fa-gear"></i> {{ __("Configure") }}</Link
            >
            <DestroyButton :delete_url="route('admin.services.destroy', service.slug)" />
          </td>
        </tr>
      </template>
    </Table>
    <small>For inquiries regarding custom order forms, please contact us at <a href="mailto:support@microelephant.io">support@microelephant.io</a></small>
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
  props: ["data", "services", "filters"],
  data() {
    return {
      tableOptions: {
        titles: [
          {
            name: this.__("Name"),
            className: "col-md-6",
          },

          {
            name: this.__("Action"),
            className: "col-md-6 text-end",
          },
        ],
      },
    };
  },
};
</script>
