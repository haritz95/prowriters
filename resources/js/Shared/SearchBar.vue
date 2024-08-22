<template>
  <div class="row align-items-center mb-4">
    <div class="col-md-8">
      <slot name="leftArea" v-if="$slots.leftArea"></slot>
      <div class="form-check" v-if="!hide_inactive_search">
        <input
          v-model="form.inactive"
          class="form-check-input"
          type="checkbox"
          value=""
          id="flexCheckDefault"
        />
        <label class="form-check-label" for="flexCheckDefault">
          {{ __("Include Inactive items") }}
        </label>
      </div>
    </div>
    <div class="col-md-4">
      <input
        v-model="form.search"
        type="text"
        class="form-control form-control-sm float-end"
        :placeholder="__('Search')"
      />
    </div>
  </div>
</template>
<script>
import debounce from "lodash/debounce";

export default {
  props: ["url", "filters", "hide_inactive_search"],
  watch: {
    form: {
      handler(val) {
        this.search(val);
      },
      deep: true,
    },
  },
  data() {
    return {
      form: {
        search: this.filters && this.filters.search ? this.filters.search : "",
        inactive:
          this.filters && this.filters.inactive == "true" ? true : false,
      },
    };
  },
  methods: {
    search: debounce(function () {
      if (this.hide_inactive_search) {
        delete this.form["inactive"];
      }
      this.$inertia.get(
        this.url,
        { filters: this.form },
        { preserveState: true, replace: true }
      );
    }, 300),
  },
};
</script>