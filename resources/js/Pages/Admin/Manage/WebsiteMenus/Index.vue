<template>
  <AppHead :title="data.title" />
  <ManageContentLayout :content_language="content_language" :title="data.title">
    <template v-slot:action>
      <AddButton
        :href="
          route('admin.manage.content.websiteMenus.create', content_language)
        "
      />
    </template>

    <Table
      :options="tableOptions"
      :links="website_menus.links"
      :total="website_menus.total"
    >
      <template v-slot>
        <tr v-for="(websiteMenu, index) in website_menus.data" :key="index">
          <td>
            <Link
              :href="
                route('admin.manage.content.websiteMenus.edit', [
                  content_language,
                  websiteMenu.id,
                ])
              "
              >{{ websiteMenu.name }}</Link
            >
            <!-- <div class="text-muted"><small>{{ websiteMenu.position }}</small></div> -->
          </td>
          <!-- <td>
            {{ websiteMenu.rating }}
          </td> -->
          <td class="col-md-2 text-end">
            <DestroyButton
              :delete_url="
                route('admin.manage.content.websiteMenus.destroy', [
                  content_language,
                  websiteMenu.id,
                ])
              "
            />
          </td>
        </tr>
      </template>
    </Table>
  </ManageContentLayout>
</template>

<script>
import ManageContentLayout from "../Partials/ManageContentLayout.vue";

import SearchBar from "../../../../Shared/SearchBar.vue";

import DestroyButton from "../../../../components/Form/DestroyButton.vue";

import Table from "../../../../components/Table.vue";

export default {
  components: {
    ManageContentLayout,
    SearchBar,
    Table,

    DestroyButton,
  },
  props: ["data", "content_language", "website_menus", "filters"],
  data() {
    return {
      tableOptions: {
        titles: [
          {
            name: this.__("Name"),
            className: "col-md-6",
          },
          // {
          //   name: this.__("Parent"),
          //   className: "col-md-4",
          // },
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