<template>
  <Modal :title="data.title">
    <form
      @submit.prevent="
        existing_record
          ? form.patch(
              route('admin.manage.content.faqQuestions.update', [
                content_language,
                existing_record.id,
              ])
            )
          : form.post(
              route('admin.manage.content.faqQuestions.store', content_language)
            )
      "
    >
      <Input
        v-model="form.title"
        name="title"
        :label="__('Title')"
        :required="true"
      />
      
      <TextArea
        v-model="form.description"
        name="description"
        :label="__('Description')"
        :required="true"
      />

      <Select
        :multiple="true"
        v-model="form.categories"
        :options="data.dropdowns.categories"
        name="categories"
        :label="__('Categories')"
        :required="true"
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
} from "../../../../../components/Form/Index.js";

export default {
  components: {
    Input,
    TextArea,
    Select,
    SubmitButton,
  },
  props: ["data", "content_language", "existing_record"],
  data() {
    return {
      form: this.$inertia.form(this.prepareForm()),
    };
  },
  methods: {
    prepareForm() {
      let inputs = {
        title: null,
        description: null,
        categories: null,
      };
      if (this.existing_record) {
        inputs = { ...inputs, ...this.existing_record };
      }
      return inputs;
    },
  },
};
</script>