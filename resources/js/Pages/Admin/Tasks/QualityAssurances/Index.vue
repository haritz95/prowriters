<template>
  <TaskShowLayout :task="task" :activeTab="tab">
    <div v-if="task.submitted_works">
      <div class="btask mt-2 fs-8">
        <div class="bg-light p-2">{{ __("Submitted Work") }}</div>
        <div class="card">
          <div class="card-body">
            <CommentMessage :comment="task.submitted_works[0]" />
          </div>
        </div>
      </div>
      <DialogLink
        :href="route('admin.tasks.qa.create', task.uuid)"
        class="btn btn-primary btn-success"
        >{{ __("Submit your feedback") }}</DialogLink
      >
    </div>
    <div class="text-center" v-else>
      {{ __("There is no work for QA yet") }}
    </div>
  </TaskShowLayout>
</template>

<script>
import TaskShowLayout from "../Partials/TaskShowLayout.vue";
import CommentMessage from "../../../../Shared/CommentMessage.vue";

export default {
  props: ["task", "data", "tab"],
  components: {
    TaskShowLayout,
    CommentMessage,
  },
};
</script>