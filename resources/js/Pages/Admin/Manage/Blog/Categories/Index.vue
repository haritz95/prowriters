<template>
  <AppHead :title="data.title" />
  <ManageContentLayout :content_language="content_language" :title="data.title">
    <template v-slot:action>
      <AddButton
        :href="
          route('admin.manage.content.postCategories.create', content_language)
        "
      />
    </template>

    <SearchBar :url="route('admin.manage.content.postCategories.index', content_language)" :filters="filters.filters" :hide_inactive_search="true" />

    <Table
      :options="tableOptions"
      :links="postCategories.links"
      :total="postCategories.total"
    >
      <template v-slot>
        <tr v-for="(postCategory, index) in postCategories.data" :key="index">
          <td>
            <Link
              :href="
                route('admin.manage.content.postCategories.edit', [
                  content_language,
                  postCategory.id,
                ])
              "
              >{{ postCategory.name }}</Link
            >            
          </td>       
          <td class="col-md-2 text-end">
            <DestroyButton
              :delete_url="
                route('admin.manage.content.postCategories.destroy', [
                  content_language,
                  postCategory.id,
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
import ManageContentLayout from "../../Partials/ManageContentLayout.vue";
import DestroyButton from "../../../../../components/Form/DestroyButton.vue";
import Table from "../../../../../components/Table.vue";
import SearchBar from "../../../../../Shared/SearchBar.vue";

export default {
  components: {
    ManageContentLayout,   
    Table,
    DestroyButton,
    SearchBar,
  },
  props: ["data", "content_language", "postCategories", "filters"],
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