<template>
  <SearchBar
    :url="route(data.routes.index)"
    :filters="filters.filters"
    :hide_inactive_search="true"
  >
    <template v-slot:leftArea>
      {{ data.title }}
    </template>
  </SearchBar>

  <Table :options="tableOptions" :links="contents.links" :total="contents.total">
    <template v-slot>
      <tr v-for="(content, index) in contents.data" :key="index">
        <td>
          <Link :href="route(data.routes.edit, content.uuid)">{{ content.title }}</Link>
        </td>
        <td class="col-md-2 text-end">
          <DestroyButton :delete_url="route(data.routes.destroy, content.uuid)" />
        </td>
      </tr>
    </template>
  </Table>
</template>

<script>
import SearchBar from "../../Shared/SearchBar.vue";
import DestroyButton from "../../components/Form/DestroyButton.vue";
import Table from "../../components/Table.vue";

export default {
  components: {
    SearchBar,
    Table,
    DestroyButton,
  },
  props: ["data", "contents", "filters"],
  data() {
    return {
      tableOptions: {
        titles: [
          {
            name: this.__("Title"),
            className: "col-md-8",
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
