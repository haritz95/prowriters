<template>
  <div class="mb-3 d-grid gap-2">
    <button :disabled="disabled" type="button" class="btn btn-sm btn-success" @click.stop="handleClick">
       <i class="fa-solid fa-magnifying-glass"></i> {{ __("Search") }}
    </button>
  </div>
</template>

<script>
export default {
  props: ["disabled"],
  methods: {
    handleClick() {
      this.$emit("click");
    },
  },
};
</script>