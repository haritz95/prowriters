<template>
  <div class="row">
    <div class="col-md-6">
      <h5 class="mb-0">{{ title }}</h5>
    </div>
    <div class="col-md-6">
      <div class="float-end">
        <Link v-if="previous_link" :href="previous_link" class="btn btn-sm btn-light mx-1"
          ><i class="fa-solid fa-angle-left"></i> {{ previous_link_text }}</Link
        >

        <AddButton :href="create_link" />
        <!-- <Link v-if="create_link" :href="create_link" class="btn btn-sm btn-primary mx-1"
          ><i class="fa-solid fa-plus"></i> {{ create_link_text }}</Link
        >
        -->
      </div>
    </div>
    <div class="col-md-12">
      <hr />
    </div>
  </div>
</template>

<script>
export default {
  props: [
    "title",
    "create_link",
    "create_link_text",
    "previous_link",
    "previous_link_text",
  ],
};
</script>
