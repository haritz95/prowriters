<template>
  <AppHead :title="data.title" />
  <div class="container page-container">
    <div class="row">
      <div class="col-md-12">
        <div class="d-flex justify-content-between flex-column d-sm-flex flex-sm-row">
          <Link class="btn btn-sm btn-light" :href="route('admin.announcements.index')">
            <i class="fa-solid fa-arrow-left-long"></i>
            {{ __("Back to Announcements") }}
          </Link>

          <div id="right-side">
            <Link
              class="btn btn-light btn-sm me-2"
              :href="route('admin.announcements.edit', announcement.uuid)"
            >
              <i class="fa-regular fa-pen-to-square"></i> {{ __("Edit") }}
            </Link>

            <button @click="destroy" class="btn btn-danger btn-sm me-2" href="#">
              <i class="fa-solid fa-trash-can"></i> {{ __("Delete") }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <div class="mb-4 mt-4">
      <hr />
      <small class="text-muted">{{ __("Title") }} : </small>
      <h3 class="text-muted">{{ announcement.title }}</h3>
      <small class="text-muted">{{ localDateTime(announcement.created_at) }}</small>
      
    </div>
    <small class="text-muted">{{ __("Content") }} : </small>
    <div v-html="announcement.content"></div>


  </div>
</template>

<script>
export default {
  props: ["data", "announcement"],
  methods: {
    destroy() {
      this.deleteConfirmDialog(
        this,
        route("admin.announcements.destroy", this.announcement.uuid)
      );
    },
  },
};
</script>
