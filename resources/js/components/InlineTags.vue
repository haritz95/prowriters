<template>
  <span
    v-for="(tag, index) in tags"
    :key="index"
    class="badge rounded-pill me-2"
    :style="{ color: tag.color, backgroundColor: tag.bg_color }"
    >{{ tag.name }}</span
  >
</template>
<script>
export default {
  props: ["tags"],
};
</script>
