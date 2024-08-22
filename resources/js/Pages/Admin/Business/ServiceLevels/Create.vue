<template>
  <Modal :title="data.title">
    <form
      @submit.prevent="
        existing_record
          ? form.patch(route('admin.serviceLevels.update', existing_record.id))
          : form.post(route('admin.serviceLevels.store'))
      "
    >
 
      <Input
        v-model="form.name"
        name="name"
        :label="__('Name')"
        :required="true"
      />

      <Input
        v-model="form.price"
        name="price"
        :label="__('Price')"
        :required="true"
        @keypress="onlyNumber($event, form.price)"
      />

      <TextArea
        v-model="form.description"
        name="description"
        :label="__('Description')"        
        rows="2"
      />

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
        price: null,
        description: null,
        is_default: null,
      };
      if (this.existing_record) {
        inputs = { ...inputs, ...this.existing_record };
      }
      return inputs;
    },
  },
};
</script>