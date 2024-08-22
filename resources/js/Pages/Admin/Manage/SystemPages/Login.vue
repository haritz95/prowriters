<template>
  <AppHead :title="data.title" />
  <ManageContentLayout :content_language="content_language" :title="data.title">
    <form
      @submit.prevent="
        form.patch(
          route(
            'admin.manage.content.systemPages.login.update',
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

      <fieldset class="border rounded-3 p-3 mb-4">
        <legend class="float-none w-auto px-3 fs-6">
          {{ __("Welcome Section") }}
        </legend>

        <Input
          v-model="form.additional_data.welcome_title"
          :label="__('Welcome Title')"
          name="additional_data.welcome_title"
          :required="true"
        />
        <Input
          v-model="form.additional_data.welcome_sub_title"
          :label="__('Welcome Sub Title')"
          name="additional_data.welcome_sub_title"
        />

        <div class="row">
          <div class="col-md-6">
            <FileChooser
              v-model="form.additional_data.welcome_image"
              :label="__('Image')"
              name="additional_data.welcome_image"
            />
          </div>
          <div class="col-md-6">
            <Input
              v-model="form.additional_data.welcome_image_alt_text"
              :label="__('Image Alt Text')"
              name="additional_data.welcome_image_alt_text"
            />
          </div>
        </div>        
        
        <div class="row">
          <div class="col-md-6">
            <ColorPicker v-model="form.additional_data.welcome_background_color" :label="__('Background Color')" name="additional_data.welcome_background_color"/>
          </div>
        </div>

      </fieldset>

      <fieldset class="border rounded-3 p-3 mb-4">
        <legend class="float-none w-auto px-3 fs-6">{{ __("SEO") }}</legend>
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
        />
      </fieldset>

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
  ColorPicker,
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
    ColorPicker,
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
        additional_data: {
          // Welcome
          welcome_title: null,
          welcome_sub_title: null,
          welcome_image: null,
          welcome_image_alt_text: null,
          welcome_background_color: null,
          // Seo
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