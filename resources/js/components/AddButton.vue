<template>
  <DialogLink :href="url" class="btn btn-sm btn-primary">
    <i class="fa-solid fa-plus"></i>
    {{ __("Add new") }}
  </DialogLink>
</template>

<script>
export default {
  props: ["url"],
};
</script>