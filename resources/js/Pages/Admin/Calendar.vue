<template>
  <AppHead :title="data.title" />
  <div class="container page-container">
    <PageTitle :title="data.title">
      <Link class="btn btn-sm btn-light me-2" :href="route('admin.tasks.index')">
        <i class="fa-solid fa-arrow-left-long"></i> {{ __("back to Tasks") }}</Link
      >
    </PageTitle>
    <Calendar :events="data.events" :user_type="'admin'" />
  </div>
</template>

<script>
import Calendar from "../../components/Calendar.vue";

export default {
  components: {
    Calendar,
  },
  props: ["data"],
};
</script>
