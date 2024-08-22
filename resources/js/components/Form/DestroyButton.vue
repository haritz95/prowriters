<template>
  <button @click="destroy" class="btn btn-danger btn-sm" href="#"
    ><i class="fa-solid fa-trash-can"></i
  ></button>
</template>

<script>
export default {
  props: ["delete_url"],
  methods: {
    destroy() {
      this.deleteConfirmDialog(this, this.delete_url);
    },
  },
};
</script>
