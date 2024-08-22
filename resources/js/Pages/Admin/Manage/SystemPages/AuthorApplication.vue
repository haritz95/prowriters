<template>
  <AppHead :title="data.title" />
  <ManageContentLayout :content_language="content_language" :title="data.title">
    <form
      @submit.prevent="
        form.patch(
          route(
            'admin.manage.content.systemPages.authorApplication.update',
            content_language
          )
        )
      "
    >
      <Input
        v-model="form.title"
        :label="__('Title')"
        name="title"
        :required="true"
      />
      <Input
        v-model="form.sub_title"
        :label="__('Sub Title')"
        name="sub_title"
      />

      <Input
        v-model="form.additional_data.form_title"
        :label="__('Application Form Title')"
        name="additional_data.form_title"
        :required="true"
      />
      <Input
        v-model="form.additional_data.form_sub_title"
        :label="__('Application Form Sub Title')"
        name="additional_data.form_sub_title"
      />

      <Input
        v-model="form.additional_data.form_submission_message"
        :label="__('Message to display after successful form submission')"
        name="additional_data.form_submission_message"
        :required="true"
      />

      <Editor
        v-model="form.content"
        :label="__('Instruction for Authors')"
        name="content"
      />

      <fieldset class="border rounded-3 p-3 mb-4">
        <legend class="float-none w-auto px-3">{{ __("SEO") }}</legend>
        <Input
          v-model="form.additional_data.meta_title"
          :label="__('Meta Title')"
          name="additional_data.meta_title"
          :required="true"
        />
        <Input
          v-model="form.additional_data.meta_description"
          :label="__('Meta Description')"
          name="additional_data.meta_description"
          :required="true"
        />

        <Input
          v-model="form.additional_data.meta_keywords"
          :label="__('Meta Keywords')"
          name="additional_data.meta_keywords"
          :required="true"
        />

        <FileChooser
          v-model="form.additional_data.meta_image"
          :label="__('Meta Image')"
          name="additional_data.meta_image"
          :required="true"
        />
      </fieldset>
      <SubmitButton :disabled="form.processing" />
    </form>
  </ManageContentLayout>
</template>
    
    <script>
import ManageContentLayout from "../Partials/ManageContentLayout.vue";
import Editor from "../../../../components/Editor.vue";
import {
  Input,
  TextArea,
  FileChooser,
  SubmitButton,
  Select,
} from "../../../../components/Form/Index.js";
export default {
  props: ["data", "existing_record", "content_language"],
  components: {
    ManageContentLayout,
    Input,
    SubmitButton,
    TextArea,
    FileChooser,
    Select,
    Editor,
  },
  data() {
    return {
      form: this.$inertia.form(this.prepareForm()),
      formConfig: {
        preserveScroll: true,
        onSuccess: () => this.form.reset(),
      },
    };
  },
  methods: {
    prepareForm() {
      let inputs = {
        title: null,
        sub_title: null,
        content: null,
        additional_data: {
          form_title: null,
          form_sub_title: null,
          form_submission_message: null,
          meta_title: null,
          meta_description: null,
          meta_keywords: null,
          meta_image: null,
        },
      };
      if (this.existing_record) {
        inputs = { ...inputs, ...this.existing_record };
      }
      return inputs;
    },
  },
};
</script>