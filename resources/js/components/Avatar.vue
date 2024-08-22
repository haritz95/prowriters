<template>
  <img class="avatar rounded-circle me-2" :src="src" loading="lazy" />
</template>
<script>
export default {
  props: ["src"],
};
</script>