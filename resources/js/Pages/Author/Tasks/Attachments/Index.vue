<template>
  <TaskShowLayout :task="task" :activeTab="'attachment'">
    <div class="d-flex justify-content-between mb-4">
      <div>
        <h4>{{ __("Attachments") }}</h4>
      </div>
      <div>
        <DialogLink
          :href="data.urls.create_attachment"
          class="btn btn-sm btn-primary"
        >
          {{ __("Upload File") }}</DialogLink
        >
      </div>
    </div>

    <Table
      :tableStyle="tableStyle"
      :options="options"
      :links="attachments.link"
      :total="attachments.total"
    >
      <tr v-for="(attachment, index) in attachments.data" :key="index">
        <td>{{ attachment.display_name }}</td>
        <td>{{ localDate(attachment.created_at) }}</td>
        <td class="text-end">
          <div class="btn-group">
            <button
              type="button"
              class="btn btn-link"
              data-bs-toggle="dropdown"
              aria-expanded="false"
            >
              <i class="fas fa-ellipsis-v"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
              <li>
                <button class="dropdown-item" type="button">
                  <a :href="route('attachments.download', attachment.uuid)">{{
                    __("Download")
                  }}</a>
                </button>
              </li>
              <li>
                <button
                  v-if="attachment.user_id == $page.props.auth_user.id"
                  @click="deleteAttachment(attachment.uuid)"
                  class="dropdown-item"
                  type="button"
                >
                  {{ __("Remove") }}
                </button>
              </li>
            </ul>
          </div>
        </td>
      </tr>
    </Table>
  </TaskShowLayout>
</template>

<script>
import TaskShowLayout from "../Partials/TaskShowLayout.vue";
import Table from "../../../../components/Table.vue";
export default {
  props: ["task", "data", "attachments"],
  components: {
    TaskShowLayout,
    Table,
  },
  data() {
    return {
      tableStyle: "table-sm",
      options: {
        titles: [
          {
            name: this.__("Name"),
            className: "col-md-5",
          },
          {
            name: this.__("Created"),
            className: "col-md-5",
          },
          {
            name: null,
            className: "col-md-2 text-end",
          },
        ],
      },
    };
  },
  methods: {
    handleAttachment(files) {
      this.form.files = files;
    },
    deleteAttachment(attachmentId) {
      let url = route("author.tasks.attachments.destroy", [
        this.task.uuid,
        attachmentId,
      ]);
      this.deleteConfirmDialog(this, url);
    },
  },
};
</script>