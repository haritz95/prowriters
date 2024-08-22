<template>
  <AppHead :title="data.title" />
  <ManageContentLayout :content_language="content_language" :title="data.title">
    <form
      @submit.prevent="
        form.patch(
          route(
            'admin.manage.content.systemPages.contact.update',
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

      <FileChooser v-model="form.image" :label="__('Image')" name="image" :required="true" />

      <div class="row">
        <div class="col-md-6">
          <Select
            v-model="form.image_position"
            :options="data.dropdowns.image_positions"
            :label="__('Image Position')"
            name="image_position"
            :required="true"
          />
        </div>
        <div class="col-md-6">
          <Input
            v-model="form.image_alt_text"
            :label="__('Image Alt Text')"
            name="image_alt_text"
            :required="true"
          />
        </div>
      </div>

      <Input
        v-model="form.additional_data.form_title"
        :label="__('Contact Form Title')"
        name="additional_data.form_title"
        :required="true"
      />
      <Input
        v-model="form.additional_data.form_sub_title"
        :label="__('Contact Form Sub Title')"
        name="additional_data.form_sub_title"
      />

      <Input
        v-model="form.additional_data.form_submission_message"
        :label="__('Message to display after Successful Form Submission')"
        name="additional_data.form_submission_message"
        :required="true"
      />

      <TextArea v-model="form.content" :label="__('Content')" name="content" />

      <TextArea
        v-model="form.meta_tags"
        :label="__('Meta Tags')"
        name="meta_tags"
      />

      <SubmitButton :disabled="form.processing" />
    </form>
  </ManageContentLayout>
</template>
    
    <script>
import ManageContentLayout from "../Partials/ManageContentLayout.vue";
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
        meta_tags: null,
        image: null,
        image_alt_text: null,
        image_position: null,
        additional_data: {
          form_title: null,
          form_sub_title: null,
          form_submission_message: null,
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