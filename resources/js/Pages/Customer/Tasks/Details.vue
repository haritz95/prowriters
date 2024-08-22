<template>
  <TaskShowLayout :task="task" activeTab="taskInformation">
    <TaskBrief :briefs="data.briefs" :task="task" :commonBriefs="commonBriefs" />
  </TaskShowLayout>
</template>

<script>
import TaskBrief from "../../../Shared/TaskBrief.vue";
import TaskShowLayout from "./Partials/TaskShowLayout.vue";

export default {
  props: ["task", "data"],
  components: {
    TaskShowLayout,
    TaskBrief,
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
          value: this.localDateTime(this.task.dead_line),
        },
        {
          label: this.__("Revisions"),
          value: this.task.revisions_taken + " / " + this.task.revisions_allowed,
        },
        {
          label: this.__("Total Cost"),
          value: this.formatMoney(this.task.total),
        },
      ],
    };
  },
};
</script>
