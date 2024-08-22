<template>
  <TaskShowLayout :task="task" :activeTab="tab">
    <div class="d-flex justify-content-between">
      <div></div>
      <div>
        <DialogLink
        v-if="isSubmitWorkButtonEnabled"
        :href="data.urls.submit_work"
        class="btn btn-sm btn-primary me-2"
      >
        <i class="fas fa-file-upload"></i> {{ __("Submit Work") }}</DialogLink
      >
      </div>
    </div>

    <div
      class="btask mt-2 mb-5 fs-8"
      v-for="(work, index) in works"
      :key="index"
    >
      <div v-if="works.length > 1" class="h4 text-center bg-light">
        {{ __("Submission") }} : {{ works.length - index }}
      </div>
      <div class="row g-0">
        <div :class="($page.props.is_quality_control_enable) ? 'col-md-4' : 'col-md-6'">
          <div class="bg-light p-2">{{ __("Author") }}</div>
          <CommentMessage :comment="work" />
        </div>
        <div class="col-md-4" v-if="$page.props.is_quality_control_enable">
          <div class="bg-light p-2">{{ __("QA") }}</div>
          <CommentMessage
            v-if="work.quality_assurance"
            :comment="work.quality_assurance"
          />
        </div>
        <div :class="($page.props.is_quality_control_enable) ? 'col-md-4' : 'col-md-6'">
          <div class="bg-light p-2">{{ __("Customer") }}</div>
          <CommentMessage
            v-if="work.revision_request"
            :comment="work.revision_request"
          />
        </div>
      </div>
    </div>
    <div v-if="works.length == 0" class="text-center">
      {{ __("No work has been submitted yet") }}
    </div>
  </TaskShowLayout>
</template>

<script>
import TaskShowLayout from "../Partials/TaskShowLayout.vue";
import CommentMessage from "../../../../Shared/CommentMessage.vue";
import SendMessage from "../../../../components/SendMessage.vue";

export default {
  props: ["tab", "task", "data", "works"],
  components: {
    TaskShowLayout,
    CommentMessage,
    SendMessage,
  },
  computed : {
    isSubmitWorkButtonEnabled() {
      return this.data.statuses_allows_submitting_work.includes(
        this.task.task_status_id
      );
    },
  },
  updated() {
    this.displaySubmitWork = false;
  },
  data() {
    return {
      displaySubmitWork: false,
    };
  },
  methods: {
    handleAttachment(files) {
      this.form.files = files;
    },
  },

};
</script>