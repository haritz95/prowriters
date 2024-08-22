<template>
  <TaskShowLayout :task="task" activeTab="content">
    <div class="row">
      <div class="col-md-8">
        <ContentPreview
          :content_preview="content_preview"
          :url_post_comment="route('admin.tasks.content.comment', this.task.uuid)"
        />
      </div>
      <div class="col-md-4">
        <div class="d-grid gap-2 mb-2">
          <DialogLink :href="route('admin.tasks.works.create', this.task.uuid)" class="btn btn-success">
            <i class="fa-solid fa-upload"></i> {{ __("Submit Work") }}</DialogLink
          >
          <DialogLink :href="route('admin.tasks.content.edit', this.task.uuid)" class="btn btn-primary">
            <i class="fa-solid fa-tv"></i> {{ __("Enter Content Preview") }}</DialogLink
          >
        </div>        

        <DownloadWork :task="task" :data="data" />
      </div>
    </div>
  </TaskShowLayout>
</template>

<script>
import TaskShowLayout from "../Partials/TaskShowLayout.vue";
import DownloadWork from "../Partials/DownloadWork.vue";
import ContentPreview from "../../../../components/ContentPreview.vue";

export default {
  props: ["task", "content_preview", "data"],
  components: {
    TaskShowLayout,
    DownloadWork,
    ContentPreview,
  },
};
</script>
