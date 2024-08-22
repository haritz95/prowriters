<template>
  <Modal :title="data.title">
    <form
      @submit.prevent="form.post(route('admin.services.update', existing_record.id))"
    >
      <Input v-model="form.name" name="name" :label="__('Name')" :required="true" />
      <!-- <Input
        v-model="form.minimum_order_quantity"
        name="minimum_order_quantity"
        :label="__('Minimum order quantity')"
        :required="true"
      /> -->

      <Input
        v-model="form.allowed_file_extensions"
        name="allowed_file_extensions"
        :label="__('Allowed file extensions')"
        :required="true"
      />

      <Input
        v-model="form.maximum_file_size"
        name="maximum_file_size"
        :label="__('Maximum file size')"
        :required="true"
      />

      <Input
        v-model="form.maximum_number_of_files_to_upload"
        name="maximum_number_of_files_to_upload"
        :label="__('Maximum number of files to upload')"
        :required="true"
      />

  

      <div class="mt-4">
        <CheckBox v-model="form.inactive" name="inactive" :label="__('Inactive')" />
      </div>

      <SubmitButton :disabled="form.processing" />
    </form>
  </Modal>
</template>

<script>
import { Input, CheckBox, SubmitButton } from "../../../../components/Form/Index.js";

export default {
  components: {
    Input,
    CheckBox,
    SubmitButton,
  },
  props: ["data", "existing_record"],
  data() {
    return {
      form: this.$inertia.form(this.prepareForm()),
      formConfig: {
        preserveScroll: false,
      },
    };
  },
  methods: {
    prepareForm() {
      let inputs = {
        name: null,
        minimum_order_quantity: null,
        allowed_file_extensions: null,
        maximum_file_size: null,
        minimum_number_of_files_to_upload: null,
        maximum_number_of_files_to_upload: null,
        disable_writing: null,
        disable_editing: null,
        disable_proofreading: null,
        inactive: null,
      };
      if (this.existing_record) {
        inputs = { ...inputs, ...this.existing_record };
      }
      return inputs;
    },
  },
};
</script>
