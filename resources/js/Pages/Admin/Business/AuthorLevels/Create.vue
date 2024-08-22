<template>
  <Modal :title="data.title">
    <form
      @submit.prevent="
        existing_record
          ? form.patch(route('admin.authorLevels.update', existing_record.slug))
          : form.post(route('admin.authorLevels.store'))
      "
    >
      <Select
        v-model="form.numeric_value"
        :options="data.dropdowns.numeric_values"
        :label="__('Level in numeric value')"
        name="numeric_value"
      />

      <Input v-model="form.name" name="name" :label="__('Name')" :required="true" />

      <Input v-model="form.description" name="description" :label="__('Description')" />

      <Input
        v-model="form.percentage"
        :label="__('Markup percentage to add to the price of the service')"       
        name="percentage"
        @keypress="onlyNumber($event, form.percentage)"
      />

      <CheckBox v-model="form.is_popular" name="is_popular" :label="__('Is Popular')" />

      <CheckBox
        v-model="form.is_default"
        name="is_default"
        :label="__('Default Selection')"
      />

      <SubmitButton :disabled="form.processing" />
    </form>
  </Modal>
</template>

<script>
import {
  Input,
  TextArea,
  Select,
  SubmitButton,
  CheckBox,
} from "../../../../components/Form/Index.js";

export default {
  components: {
    Input,
    Select,
    TextArea,
    SubmitButton,
    CheckBox,
  },
  props: ["data", "existing_record"],
  data() {
    return {
      form: this.$inertia.form(this.prepareForm()),
    };
  },
  methods: {
    prepareForm() {
      let inputs = {
        name: null,
        description: null,
        is_popular: null,
        is_default: null,
        numeric_value: 1,
        percentage: 1,
      };
      if (this.existing_record) {
        inputs = { ...inputs, ...this.existing_record };
      }
      return inputs;
    },
  },
};
</script>
