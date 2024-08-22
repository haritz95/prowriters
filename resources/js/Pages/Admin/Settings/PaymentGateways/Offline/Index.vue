<template>
  <SettingsLayout :title="data.title">
    <ActionToolBar :toolbar="toolbar" />
    <small>*** {{ data.note }} *** </small>
    <SearchBar :url="data.urls.search" :filters="filters.filters" />

    <Table
      :options="tableOptions"
      :links="methods.links"
      :total="methods.total"
    >
      <template v-slot>
        <tr v-for="(methods, index) in methods.data" :key="index">
          <td>
            <Link
              :href="
                route('admin.settings.offline.payment.methods.edit', methods.slug)
              "
              >{{ methods.name }}</Link
            >
          </td>
          <td>
            {{ methods.description }}
          </td>
          <td class="col-md-2 text-end">
            <DestroyButton
              :delete_url="
                route(
                  'admin.settings.offline.payment.methods.destroy',
                  methods.id
                )
              "
            />
          </td>
        </tr>
      </template>
    </Table>
  </SettingsLayout>
</template>

<script>
import SettingsLayout from "../../Partials/SettingsLayout.vue";
import SearchBar from "../../../../../Shared/SearchBar.vue";
import ActionToolBar from "../../Partials/ActionToolBar.vue";
import DestroyButton from "../../../../../components/Form/DestroyButton.vue";

import Table from "../../../../../components/Table.vue";

export default {
  components: {
    SettingsLayout,
    SearchBar,
    Table,
    ActionToolBar,
    DestroyButton,
  },
  props: ["data", "methods", "filters"],
  data() {
    return {
      toolbar: {
        title: this.data.title,
        hide_save_button: true,
        links: {
          create: {
            title: this.data.create_link_text,
            url: this.data.urls.new_item,
          },
        },
      },
      tableOptions: {
        titles: [
          {
            name: this.__("Name"),
            className: "col-md-6",
          },
          {
            name: this.__("Description"),
            className: "",
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