<template>
  <div class="">
    <div class="">
      <form @submit.prevent="form.get(data.urls.search, formConfig)">
        <Select
          :searchable="true"
          :clearable="true"
          :options="data.dropdowns.services"
          v-model="form.filters.service_id"
          :label="__('Service')"
          name="service_id"
        />

        <Select
          :searchable="true"
          :clearable="true"
          :options="data.dropdowns.subjects"
          v-model="form.filters.subject_id"
          :label="__('Subject')"
          name="subject_id"
        />

        <Select
          :searchable="true"
          :clearable="true"
          :options="data.dropdowns.author_levels"
          v-model="form.filters.author_level_id"
          :label="__('Author Level')"
          name="author_level_id"
        />

        <Select
          :searchable="true"
          :clearable="true"
          :options="data.dropdowns.education_levels"
          v-model="form.filters.education_level_id"
          :label="__('Education Level')"
          name="education_level_id"
        />

        <Input v-model="form.filters.code" :label="__('ID')" name="id" />

        <Input v-model="form.filters.name" :label="__('Name')" name="name" />

        <Input v-model="form.filters.email" :label="__('Email')" name="email" />

        <CheckBox
          v-model="form.filters.inactive"
          name="inactive"
          :label="__('Include Inactive')"
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
          code: this.filters && this.filters.code ? this.filters.code : "",
          name: this.filters && this.filters.name ? this.filters.name : "",
          email: this.filters && this.filters.email ? this.filters.email : "",         
          subject_id:
            this.filters && this.filters.subject_id
              ? this.filters.subject_id
              : "",
          service_id:
            this.filters && this.filters.service_id
              ? this.filters.service_id
              : "",
          education_level_id:
            this.filters && this.filters.education_level_id
              ? this.filters.education_level_id
              : "",
          author_level_id:
            this.filters && this.filters.author_level_id
              ? this.filters.author_level_id
              : "",
          inactive:
            this.filters && this.filters.inactive == "true" ? true : false,
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