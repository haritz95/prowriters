<template>
  <BidLayout :bid_request="bid_request" activeTab="brief">
    <TaskBrief :briefs="data.briefs" :task="task" :commonBriefs="commonBriefs" />
  </BidLayout>
</template>

<script>
import BidLayout from "./Partials/BidLayout.vue";
import TaskBrief from "../../../Shared/TaskBrief.vue";

export default {
  props: ["bid_request", "data", "task"],
  components: {
    BidLayout,
    TaskBrief,
  },
  data() {
    return {
      commonBriefs: [
        {
          label: this.__("Created"),
          value: this.localDateTime(this.bid_request.created_at),
        },
        {
          label: this.__("Budget"),
          value: this.formatMoney(this.bid_request.budget),
        },
      ],
    };
  },
};
</script>
