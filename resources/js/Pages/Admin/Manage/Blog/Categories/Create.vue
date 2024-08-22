<template>
  <Modal :title="data.title">
    <form
      @submit.prevent="
        existing_record
          ? form.patch(
              route('admin.manage.content.postCategories.update', [
                content_language,
                existing_record.id,
              ])
            )
          : form.post(
              route(
                'admin.manage.content.postCategories.store',
                content_language
              )
            )
      "
    >
      <Input
        v-model="form.name"
        name="name"
        :label="__('Name')"
        :required="true"
      />
      
      <TextArea
        v-model="form.meta_tags"
        name="meta_tags"
        :label="__('Meta Tags')"
        :required="true"
      />

      <SubmitButton :disabled="form.processing" />
    </form>
  </Modal>
</template>

<script>
import { Input, TextArea, SubmitButton } from "../../../../../components/Form/Index.js";

export default {
  components: {
    Input,
    TextArea,
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
        name: null,
        meta_tags: null,
      };
      if (this.existing_record) {
        inputs = { ...inputs, ...this.existing_record };
      }
      return inputs;
    },
  },
};
</script>