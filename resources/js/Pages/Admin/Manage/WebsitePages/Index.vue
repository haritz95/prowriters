<template>
  <AppHead :title="data.title" />
  <ManageContentLayout :content_language="content_language" :title="data.title">
    <template v-slot:action>
      <AddButton
        :href="
          route('admin.manage.content.websitePages.create', content_language)
        "
      />
    </template>

    <SearchBar :url="route('admin.manage.content.websitePages.index', content_language)" :filters="filters.filters" :hide_inactive_search="true" />

    <Table
      :options="tableOptions"
      :links="website_pages.links"
      :total="website_pages.total"
    >
      <template v-slot>
        <tr v-for="(website_page, index) in website_pages.data" :key="index">
          <td>
            <Link
              :href="
                route('admin.manage.content.websitePages.edit', [
                  content_language,
                  website_page.id,
                ])
              "
              >{{ website_page.name }}</Link
            >            
          </td>       
          <td class="col-md-2 text-end">
            <DestroyButton
              :delete_url="
                route('admin.manage.content.websitePages.destroy', [
                  content_language,
                  website_page.id,
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
import DestroyButton from "../../../../components/Form/DestroyButton.vue";
import Table from "../../../../components/Table.vue";
import SearchBar from "../../../../Shared/SearchBar.vue";

export default {
  components: {
    ManageContentLayout,   
    Table,
    DestroyButton,
    SearchBar,
  },
  props: ["data", "content_language", "website_pages", "filters"],
  data() {
    return {
      tableOptions: {
        titles: [
          {
            name: this.__("Name"),
            className: "col-md-10",
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