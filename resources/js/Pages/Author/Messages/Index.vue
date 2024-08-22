<template>
  <AppHead :title="data.title" />
  <div class="container page-container">
    <div class="row">
      <div class="col-md-12">
        <PageTitle :title="data.title">
          <DialogLink
            class="btn btn-sm btn-outline-success me-2"
            :href="route('author.messageThreads.create')"
          >
            <i class="fa-solid fa-plus"></i> {{ __("New Message") }}
          </DialogLink>
        </PageTitle>

        <Table
          :options="tableOptions"
          :links="threads.links"
          :total="threads.total"
          :tableStyle="'fs-8'"
        >
          <template v-slot>
            <tr v-for="(thread, index) in threads.data" :key="index">
              <td class="col-md-2">
                <Link :href="route('author.messageThreads.show', thread.uuid)">{{
                  thread.number
                }}</Link>
              </td>
              <td class="col-md-8">{{ thread.subject }}</td>
              <td class="col-md-2">{{ localDate(thread.updated_at) }}</td>
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
  props: ["data", "threads", "filters"],
  data() {
    return {
      tableOptions: {
        titles: [
          {
            name: this.__("Number"),
            className: "col-md-2",
          },
          {
            name: this.__("Subject"),
            className: "col-md-8",
          },
          {
            name: this.__("Last updated"),
            className: "col-md-2",
          },
        ],
      },
    };
  },
};
</script>
