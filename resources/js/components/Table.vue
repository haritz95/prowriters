<template>
  <div class="table-responsive">
    <table class="table align-middle nowrap w-100 fs-8" :class="tableStyle">
      <thead>
        <tr>
          <template v-for="(title, index) in options.titles" :key="index">
            <th :class="title.className">{{ title.name }}</th>
          </template>
        </tr>
      </thead>
      <tbody v-if="total != 0">
        <slot></slot>
      </tbody>
    </table>
    <Pagination
      v-if="links && total > 0 && !noPagination"
      :total="total"
      :links="links"
      :only="only"
    />
    <div v-if="total == 0" class="text-center">
      <span v-if="text_no_record" v-html="text_no_record"></span>
      <span v-else>{{ __("No record found") }}</span>
    </div>
  </div>
</template>

<script>
import Pagination from "./Pagination.vue";
export default {
  components: {
    Pagination,
  },
  props: [
    "options",
    "links",
    "total",
    "tableStyle",
    "noPagination",
    "text_no_record",
    "only",
  ],
};
</script>
<style>
.table-responsive .dropdown,
.table-responsive .btn-group,
.table-responsive .btn-group-vertical {
  position: static;
}
</style>