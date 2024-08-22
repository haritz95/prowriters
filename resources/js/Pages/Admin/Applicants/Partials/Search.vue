<template>
  <div class="">

    <div class="">
      <form @submit.prevent="form.get(route('admin.applicants.index'), formConfig)">
        <Select
          :searchable="true"         
          :options="data.dropdowns.statuses"
          v-model="form.filters.applicant_status_id"
          :label="__('Status')"
          name="applicant_status_id"
        />
        

        <Input
          v-model="form.filters.search"
          :label="__('Name') + ', ' + __('Email') + ' ' + __('or') + ' ' + __('Number')"
          name="search"
        />

        <div class="d-grid gap-2">
          <button
            type="submit"
            class="btn btn-success btn-sm"
            :disabled="form.processing"
          >
            <i class="fa-solid fa-search"></i> {{ __("Search") }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>
<script>
import { Input, Select, CheckBox } from "../../../../components/Form/Index.js";

export default {
  props: ["data", "filters", "only"],
  components: {
    Input,
    Select,
    CheckBox,
  },
  data() {
    return {
      form: this.$inertia.form({
        filters: {
          search:
            this.filters && this.filters.search ? this.filters.search : "",
            applicant_status_id:
            this.filters && this.filters.applicant_status_id
              ? this.filters.applicant_status_id
              : "",          
        },
      }),
      formConfig: {
        preserveState: true,
        replace: true,
        only: this.only,
      },
    };
  },
};
</script>