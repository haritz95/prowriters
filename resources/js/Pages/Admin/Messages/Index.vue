<template>
  <AppHead :title="data.title" />
  <div class="container page-container">
    <div class="row">
      <div class="col-md-12">
        <PageTitle :title="data.title">
          <DialogLink
            class="btn btn-sm btn-primary me-2"
            :href="route('admin.messageThreads.create')"
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
              <td>
                <div>
                  <Link :href="route('admin.messageThreads.show', thread.uuid)">{{
                    thread.number
                  }}</Link>
                </div>
                {{ thread.subject }}
              </td>
              <td>{{ thread.sender.full_name }}</td>
              <td>{{ thread.recipient.full_name }}</td>
              <td>{{ localDateTime(thread.updated_at) }}</td>
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
            name: this.__("Subject"),
            className: "col-md-6",
          },
          {
            name: this.__("Created By"),
            className: "col-md-2",
          },
          {
            name: this.__("Recipient"),
            className: "col-md-2",
          },
          {
            name: this.__("Last Updated"),
            className: "col-md-2",
          },
        ],
      },
    };
  },
};
</script>
