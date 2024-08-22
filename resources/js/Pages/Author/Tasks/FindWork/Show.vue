<template>
  <AppHead :title="data.title" />
  <div class="container page-container">
    <PageTitle :title="data.title">
      
      <Link class="btn btn-sm btn-light me-2" :href="route('author.find.work.index')">
        <i class="fa-solid fa-arrow-left-long"></i>
        {{ __("Find other Tasks") }}
      </Link>
      <button type="button" class="btn btn-sm btn-success" @click="acceptTask">
        <i class="fa-sharp fa-solid fa-rocket"></i> {{ __("Accept this task") }}
      </button>
    </PageTitle>

    <TaskBrief :briefs="data.briefs" :task="task" :commonBriefs="commonBriefs" />

    <div class="row mt-4">
      <div class="col-md-12">
        <h5 class="mb-4 bg-light p-2">{{ __("Attachments") }}</h5>
        <AttachmentList :attachments="task.attachments" />
      </div>
    </div>
  </div>
</template>

<script>
import TaskShowLayout from "../Partials/TaskShowLayout.vue";

import AttachmentList from "../../../../components/AttachmentList.vue";
import TaskBrief from "../../../../Shared/TaskBrief.vue";

export default {
  props: ["task", "data"],
  components: {
    TaskShowLayout,
    TaskBrief,
    AttachmentList,
  },
  data() {
    return {
      commonBriefs: [
        {
          label: this.__("Created"),
          value: this.localDateTime(this.task.created_at),
        },
        {
          label: this.__("Deadline"),
          value: this.task.dead_line_for_author
            ? this.localDateTime(this.task.dead_line_for_author)
            : null,
        },
        {
          label: this.__("Earning"),
          value: this.formatMoney(this.task.author_payment_amount),
        },
      ],
    };
  },
  methods: {
    acceptTask() {
      let scope = this;
      this.confirmDialog(this.__("Accept"), function () {
        scope.$inertia.post(route("author.find.work.accept", scope.task.uuid));
      });
    },
  },
};
</script>
