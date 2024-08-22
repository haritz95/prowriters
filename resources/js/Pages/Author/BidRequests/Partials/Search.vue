<template>
  <div class="fs-8" id="search-form">
    <Select
      v-model="form.service"
      :searchable="true"
      :label="__('Service')"
      name="service"
      :options="data.dropdowns.services"
      bottom_margin="mb-3"
    />
    
    <Select
      v-model="form.sort_by"
      :searchable="true"
      :label="__('Sort By')"
      name="sort_by"
      :options="data.dropdowns.sort_by_options"
      bottom_margin="mb-1"
    />

    <!-- <Input
      v-model="form.task_number"
      :label="__('Task Number')"
      name="task_number"
    />

    <CheckBox
      v-model="form.show_archived"
      name="show_archived"
      :label="__('Show Archived')"
      bottom_margin="mb-0"
    /> -->

    <div class="d-grid gap-2 mt-3">
      <button @click="search" type="submit" class="btn btn-success btn-sm">
        <i class="fa-solid fa-search"></i> {{ __("Search") }}
      </button>
    </div>
  </div>
</template>


<script>
import { Select, Input, CheckBox } from "../../../../components/Form/Index.js";

export default {
  props: ["data", "filters", "only"],
  components: {
    Select,
    Input,
    CheckBox,
  },
  created() {
    if (this.filters?.search) {
      var search = this.filters.search;

      if (search?.service) {
        this.form.service = parseInt(search.service);
      }

      if (search?.sort_by) {
        this.form.sort_by = parseInt(search.sort_by);
      }
    }
  },
  methods: {
    search() {
      this.$inertia.get(
        route("author.bidRequests.index"),
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
