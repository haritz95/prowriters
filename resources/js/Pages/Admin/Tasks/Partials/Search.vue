<template>
  <div class="fs-8" id="search-form">
    <Select
      v-model="form.service"
      :searchable="true"
      :label="__('Service')"
      name="service"
      :options="data.dropdowns.services"
      bottom_margin="mb-1"
    />

    <Select
      v-model="form.status"
      :searchable="true"
      :label="__('Status')"
      name="status"
      :options="data.dropdowns.statuses"
      bottom_margin="mb-1"
    />  

    <Select
      v-model="form.author_level_id"
      :searchable="true"
      :label="__('Author Level')"
      name="author_level_id"
      :options="data.dropdowns.author_levels"
      bottom_margin="mb-1"
    />

    <DateRangePicker
      v-model="form.task_date"
      :label="__('Create Date')"
      name="task_date"
    />

    <DateRangePicker v-model="form.deadline" :label="__('Due Date')" name="due_date" />

    <Select
      v-model="form.order_by"
      clearable="true"
      :searchable="true"
      :label="__('Order By')"
      name="order_by"
      :options="data.dropdowns.order_by_options"
      bottom_margin="mb-1"
    />

    <Input v-model="form.task_number" :label="__('Task Number')" name="task_number" />

    <Input v-model="form.task_title" :label="__('Task Title')" name="task_title" />

    <SearchCustomer v-model="form.customer" :label="__('Customer')" name="customer" />

    <SearchAuthor v-model="form.author" :label="__('Author')" name="author" />

    <CheckBox
      v-model="form.not_invoiced"
      name="not_invoiced"
      :label="__('Not Invoiced')"
      bottom_margin="mb-0"
    />

    <CheckBox
      v-model="form.show_archived"
      name="show_archived"
      :label="__('Show Archived')"
      bottom_margin="mb-0"
    />

    <CheckBox
      v-model="form.not_assigned"
      name="not_assigned"
      :label="__('Not assigned to any Author')"
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
import {
  Select,
  Input,
  CheckBox,
  DateRangePicker,
  SearchCustomer,
  SearchAuthor,
} from "../../../../components/Form/Index.js";

export default {
  props: ["data", "filters", "only"],
  components: {
    Select,
    Input,
    CheckBox,
    DateRangePicker,
    SearchCustomer,
    SearchAuthor,
  },
  created() {
    if (this.filters?.search) {
      var search = this.filters.search;

      if (search?.task_date) {
        this.form.task_date = search.task_date;
      }

      if (search?.service) {
        this.form.service = parseInt(search.service);
      }
      if (search?.customer) {
        this.form.customer = parseInt(search.customer);
      }
      if (search?.author) {
        this.form.author = parseInt(search.author);
      }   

      if (search?.author_level_id) {
        this.form.author_level_id = parseInt(search.author_level_id);
      }

      if (search?.due_date) {
        this.form.due_date = parseInt(search.due_date);
      }

      if (search?.status) {
        this.form.status = parseInt(search.status);
      }

      if (search?.not_assigned) {
        this.form.not_assigned = parseInt(search.not_assigned);
      }

      if (search?.task_number) {
        this.form.task_number = parseInt(search.task_number);
      }

      if (search?.task_title) {
        this.form.task_title = parseInt(search.task_title);
      }

      if (search?.show_archived) {
        this.form.show_archived = parseInt(search.show_archived);
      }

      if (search?.not_invoiced) {
        this.form.not_invoiced = parseInt(search.not_invoiced);
      }

      if (search?.task_by_nearest_due_date) {
        this.form.task_by_nearest_due_date = parseInt(search.task_by_nearest_due_date);
      }
    }
  },
  methods: {
    search() {
      this.$inertia.get(
        route("admin.tasks.index"),
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
        task_date: "",
        service: "",
        due_date: "",
        status: "",
        customer: "",
        author: "",
        task_number: "",
        show_archived: "",
        task_by_nearest_due_date: "",
        task_title: "",
      },
    };
  },
};
</script>
