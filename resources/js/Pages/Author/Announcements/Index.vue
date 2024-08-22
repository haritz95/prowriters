<template>
  <AppHead :title="data.title" />
  <div class="container page-container">
    <div class="row">
      <div class="col-md-12">
        <PageTitle :title="data.title" />

        <Table
          :options="tableOptions"
          :links="announcements.links"
          :total="announcements.total"
          :tableStyle="'fs-8'"
        >
          <template v-slot>
            <tr v-for="(announcement, index) in announcements.data" :key="index">
              <td>
                <Link :href="route('author.announcements.show', announcement.uuid)">{{
                  announcement.number
                }}</Link>
              </td>
              <td>
                {{ announcement.title }}
              </td>
              <td>
                {{ localDateTime(announcement.created_at) }}
              </td>
            </tr>
          </template>
        </Table>
      </div>
    </div>
  </div>
</template>

<script>
import Table from "../../../components/Table.vue";
import SearchBar from "../../../Shared/SearchBar.vue";
export default {
  components: {
    Table,
    SearchBar,
  },
  props: ["data", "announcements", "filters"],
  data() {
    return {
      tableOptions: {
        titles: [
          {
            name: this.__("Number"),
            className: "col-md-2",
          },
          {
            name: this.__("Title"),
            className: "col-md-8",
          },
          {
            name: this.__("Created"),
            className: "col-md-2",
          },
        ],
      },
    };
  },
};
</script>
