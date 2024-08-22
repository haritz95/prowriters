<template>
  <AppHead :title="data.title" />
  <ManageContentLayout :content_language="content_language" :title="data.title">
    <template v-slot:action>
      <AddButton
        :href="
          route('admin.manage.content.faqQuestions.create', content_language)
        "
      />
    </template>

    <SearchBar :url="route('admin.manage.content.faqQuestions.index', content_language)" :filters="filters.filters" :hide_inactive_search="true" />

    <Table
      :options="tableOptions"
      :links="faqQuestions.links"
      :total="faqQuestions.total"
    >
      <template v-slot>
        <tr v-for="(faqQuestion, index) in faqQuestions.data" :key="index">
          <td>
            <Link
              :href="
                route('admin.manage.content.faqQuestions.edit', [
                  content_language,
                  faqQuestion.id,
                ])
              "
              >{{ faqQuestion.title }}</Link
            >            
          </td>       
          <td class="col-md-2 text-end">
            <DestroyButton
              :delete_url="
                route('admin.manage.content.faqQuestions.destroy', [
                  content_language,
                  faqQuestion.id,
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
  props: ["data", "content_language", "faqQuestions", "filters"],
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