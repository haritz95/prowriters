<template>
  <AppHead :title="data.title" />
  <div class="container page-container">
    <div class="row">
      <div class="col-md-12">
        <div class="d-flex justify-content-between flex-column d-sm-flex flex-sm-row">
          <Link class="btn btn-sm btn-light" :href="route('author.announcements.index')">
            <i class="fa-solid fa-arrow-left-long"></i>
            {{ __("Back to Announcements") }}
          </Link>
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
};
</script>
