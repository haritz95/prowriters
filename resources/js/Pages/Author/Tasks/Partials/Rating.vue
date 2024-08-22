<template>
  <div class="card mb-4" v-if="task.rating && task.rating.comment">
    <div class="card-header">
      <div>{{ __("Rating by customer") }}</div>
      <small class="text-muted"
        >{{ __("Posted") }} : {{ localDateTime(task.rating.created_at) }}</small
      >
    </div>
    <div class="card-body">
      <div class="fs-8">
        <Star :number="task.rating.number" :comment="task.rating.comment" />
      </div>
    </div>
  </div>
</template>
<script>
import Star from "../../../../components/Star.vue";

export default {
  props: ["task"],
  components: {
    Star,
  },
};
</script>
