<template>
  <div class="fs-8" id="search-form">
    <Select
      v-model="form.status"
      :searchable="true"
      :label="__('Status')"
      name="status"
      :options="data.dropdowns.statuses"
      bottom_margin="mb-1"
    />

    <Input
      v-model="form.number"
      :label="__('Bid Request Number')"
      name="name"
    />

    <div class="d-grid gap-2 mt-3">
      <button @click="search" type="submit" class="btn btn-success btn-sm">
        <i class="fa-solid fa-search"></i> {{ __("Search") }}
      </button>
    </div>
  </div>
</template>
  
  
  <script>
import { Select, Input} from "../../../../components/Form/Index.js";

export default {
  props: ["data", "filters", "only"],
  components: {
    Select,
    Input,   
  },
  created() {
    if (this.filters?.search) {
      var search = this.filters.search;
      if (search?.status) {
        this.form.status = parseInt(search.status);
      }
      if (search?.number) {
        this.form.number = parseInt(search.number);
      }
    }
  },
  methods: {
    search() {
      this.$inertia.get(
        route("admin.bidRequests.index"),
        { search: this.form },
        {
          preserveState: true,
          preserveScroll: true,
          replace: true,
          only: this.only,
        }
      );
    },
  },
  data() {
    return {
      form: {
        service: "",
        sort_by: "",
      },
    };
  },
};
</script>
  