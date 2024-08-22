<template>
  <div class="row align-items-center mb-4">
    <div class="col-md-6">
      <!-- <select
        class="form-select form-select-sm mb-4 mb-md-0"
        v-model="form.service_id"
      >
        <option value="">{{ __("All Services") }}</option>
        <option
          v-for="(service, index) in services"
          :value="service.id"
          :key="index"
        >
          {{ service.name }}
        </option>
      </select> -->
    </div>
    <div class="col-md-12">
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
  props: ["url", "filters", "services"],
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
        service_id:
          this.filters && this.filters.service_id
            ? this.filters.service_id
            : "",
      },
    };
  },
  methods: {
    search: debounce(function () {
      this.$inertia.get(
        this.url,
        { filters: this.form },
        { preserveState: true, replace: true }
      );
    }, 300),
  },
};
</script>