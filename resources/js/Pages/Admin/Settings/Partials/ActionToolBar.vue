<template>
  <div class="actions-toolbar py-2 mb-1">
    <div class="row">
      <div class="col-6 col-md-6">
        <h5 class="mb-1">{{ toolbar.title }}</h5>
      </div>
      <div class="col-6 col-md-6 text-end">
        <button
          v-if="!toolbar.hide_save_button"
          type="submit"
          name="submit"
          class="btn btn-sm btn-success me-1"
          :disabled="disable_save_button"          
        >
          <i class="fa-solid fa-floppy-disk"></i> {{ __("Save record") }}
        </button>

        <Link
          v-if="toolbar.links && toolbar.links.create"
          :href="toolbar.links.create.url"
          class="btn btn-sm btn-primary me-1"
        >
          <i class="fas fa-plus"></i> {{ toolbar.links.create.title }}
        </Link>

        <Link
          v-if="toolbar.links && toolbar.links.previous_page"
          :href="toolbar.links.previous_page.url"
          class="btn btn-sm btn-light me-1"
        >
          <i class="fa-solid fa-chevron-left"></i>
          {{ toolbar.links.previous_page.title }}
        </Link>
      </div>
    </div>
    <hr />
  </div>
</template>

<script>
export default {
  props: ["toolbar", "disable_save_button"],
  methods: {
    submitForm() {
      this.$emit("submit");
    },
  },
};
</script>