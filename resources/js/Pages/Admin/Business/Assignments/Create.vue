<template>
  <Modal :title="data.title">
    <form
      @submit.prevent="
        existing_record
          ? form.patch(data.urls.submit_form)
          : form.post(data.urls.submit_form, formConfig)
      "
    >
      <Input v-model="form.name" name="name" :label="__('Name')" :required="true" />

      <Input
        v-if="data.show_price_field"
        v-model="form.price"
        name="price"
        :label="__('Price')"
        :required="true"
        @keypress="onlyNumber($event, form.price)"
      />

      <Input
        v-model="form.number_of_revisions_allowed"
        name="number_of_revisions_allowed"
        :label="__('Number of revisions allowed')"
        :required="true"
        @keypress="onlyNumber($event, form.number_of_revisions_allowed)"
      />

      <!-- 
      <Select
        :searchable="true"
        :options="data.dropdowns.urgencies"
        v-model="form.urgency_id"
        :label="__('Turnaround Time')"
        :required="true"
        name="urgency_id"
      /> -->

      <SubmitButton :disabled="form.processing" />
    </form>
  </Modal>
</template>

<script>
import { Input, SubmitButton } from "../../../../components/Form/Index.js";

export default {
  components: {
    Input,
    SubmitButton,
  },
  props: ["data", "existing_record"],
  data() {
    return {
      form: this.$inertia.form(this.prepareForm()),
      formConfig: {
        onSuccess: () => this.form.reset(),
      },
    };
  },
  methods: {
    prepareForm() {
      let inputs = {
        name: null,
        number_of_revisions_allowed: null,
        price: 0,
        url_query_parameters: this.data.url_query_parameters,
      };
      if (this.existing_record) {
        inputs = { ...inputs, ...this.existing_record };
      }
      return inputs;
    },
  },
};
</script>
