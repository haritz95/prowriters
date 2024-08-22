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
      v-model="form.task_number"
      :label="__('Task Number')"
      name="task_number"
    />

    <CheckBox
      v-model="form.show_archived"
      name="show_archived"
      :label="__('Show Archived')"
      bottom_margin="mb-0"
    />

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

      if (search?.status) {
        this.form.status = parseInt(search.status);
      }

      if (search?.task_number) {
        this.form.task_number = parseInt(search.task_number);
      }

      if (search?.show_archived) {
        this.form.show_archived = parseInt(search.show_archived);
      }
    }
  },
  methods: {
    search() {
      this.$inertia.get(
        route("author.tasks.index"),
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
        status: "",
        task_number: "",
        show_archived: "",
      },
    };
  },
};
</script>
