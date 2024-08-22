<template>
  <div class="fs-8" id="search-form">  

    <Select
      v-model="form.status"
      :clearable="true"
      :label="__('Status')"
      name="status"
      :options="data.dropdowns.invoice_statuses"
      bottom_margin="mb-2"
    />

    <DateRangePicker
      v-model="form.due_date"
      :label="__('Due Date')"
      name="due_date"
    />

    <SearchButton @click="search" :disabled="form.processing" />
  </div>
</template>


<script>
import {
  Select,
  SearchButton,
  DateRangePicker,
} from "../../../../components/Form/Index.js";

export default {
  props: ["data", "filters", "only"],
  components: {
    Select,
    SearchButton,
    DateRangePicker,
  },
  created() {
    if (this.filters?.search) {
      var search = this.filters.search;

      if (search?.due_date) {
        this.form.due_date = parseInt(search.due_date);
      }

      if (search?.status) {
        this.form.status = parseInt(search.status);
      }
    }
  },
  methods: {
    search() {
      this.$inertia.get(
        route("admin.invoices.index"),
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
        due_date: "",
        status: "",
      },
    };
  },
};
</script>
