<template>
  <div class="">
    <div class="">
      <form @submit.prevent="form.get(route('admin.customers.index'), formConfig)">
        

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
import { Input, CheckBox } from "../../../../components/Form/Index.js";

export default {
  props: ["data", "filters", "only"],
  components: {
    Input,   
    CheckBox,
  },
  data() {
    return {
      form: this.$inertia.form({
        filters: {         
          name: this.filters && this.filters.name ? this.filters.name : "",
          email: this.filters && this.filters.email ? this.filters.email : "",      
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