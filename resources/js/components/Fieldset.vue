<template>
  <fieldset class="border rounded-3 p-3 mb-4">
    <legend class="float-none w-auto px-3 fs-8" v-html="title"></legend>
    <slot></slot>
  </fieldset>
</template>
<script>
export default {
  props: ["title"],
};
</script>



  