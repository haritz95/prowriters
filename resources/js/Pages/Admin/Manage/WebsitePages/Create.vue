<template>
  <AppHead :title="data.title" />
  <ManageContentLayout :content_language="content_language" :title="data.title">
    <template v-slot:action>
      <Link
        class="btn btn-sm btn-light"
        :href="
          route('admin.manage.content.websitePages.index', content_language)
        "
      >
        <i class="fa-solid fa-arrow-left-long"></i>
        {{ __("Back to Website Pages") }}
      </Link>
    </template>
    <form
      @submit.prevent="
        existing_record
          ? form.patch(
              route('admin.manage.content.websitePages.update', [
                content_language,
                existing_record.id,
              ])
            )
          : form.post(
              route('admin.manage.content.websitePages.store', content_language)
            )
      "
    >
      <Input
        v-model="form.name"
        name="name"
        :label="__('Name')"
        :required="true"
      />

      <fieldset class="border rounded-3 p-3 mb-4">
        <legend class="float-none w-auto px-3 fs-6">
          {{ __("Header Section") }}
        </legend>

        <CheckBox
          v-model="form.disable_auto_slug_gen"
          name="disable_auto_slug_gen"
          :label="__('Disable Auto Slug Generation')"
        />

        <Input
          v-model="form.title"
          name="title"
          :label="__('Title')"
          :required="true"
        />

        <Input
          v-model="form.slug"
          name="slug"
          :label="__('Slug')"
          :required="true"
          :readonly="!form.disable_auto_slug_gen"
        />

        <Input
          v-model="form.sub_title"
          name="sub_title"
          :label="__('Sub Title')"
        />

        <FileChooser v-model="form.image" :label="__('Image')" name="image" />

        <div class="row">
          <div class="col-md-6">
            <Select
              v-model="form.image_position"
              :options="data.dropdowns.image_positions"
              :label="__('Image Position')"
              name="image_position"
            />
          </div>
          <div class="col-md-6">
            <Input
              v-model="form.image_alt_text"
              :label="__('Image Alt Text')"
              name="image_alt_text"
            />
          </div>
        </div>

        <ColorPicker
          v-model="form.appearance.bg_color"
          :label="__('Background Color')"
          name="appearance.bg_color"
          :required="true"
        />
        <ColorPicker
          v-model="form.appearance.text_color"
          :label="__('Text Color')"
          name="appearance.text_color"
          :required="true"
        />

        <div class="row">
          <div class="col-md-4">
            <Select
              v-model="form.appearance.title_alignment"
              :options="data.dropdowns.image_positions"
              :label="__('Title Alignment')"
              name="appearance.title_alignment"
            />
          </div>
          <div class="col-md-4">
            <Select
              v-model="form.appearance.image_alignment"
              :options="data.dropdowns.image_positions"
              :label="__('Image Alignment')"
              name="appearance.image_alignment"
            />
          </div>
          <div class="col-md-4">
            <Input
              v-model="form.appearance.header_minimum_height"
              :label="__('Header Minimum Height')"
              :tooltip="__('In pixels')"
              name="appearance.header_minimum_height"
              :required="true"
            />
          </div>
        </div>
      </fieldset>

      <RichEditor
        v-model="form.content"
        :label="__('Content')"
        :required="true"
      />

      <TextArea
        v-model="form.meta_tags"
        :label="__('Meta Tags')"
        name="meta_tags"
        :required="true"
      />

      <SubmitButton :disabled="form.processing" />
    </form>
  </ManageContentLayout>
</template>

<script>
import ManageContentLayout from "../Partials/ManageContentLayout.vue";
import RichEditor from "../../../../components/RichEditor.vue";
import {
  Input,
  TextArea,
  Select,
  SubmitButton,
  ColorPicker,
  FileChooser,
  CheckBox,
} from "../../../../components/Form/Index.js";

export default {
  components: {
    ManageContentLayout,
    Input,
    TextArea,
    Select,
    SubmitButton,
    ColorPicker,
    FileChooser,
    CheckBox,
    RichEditor,
  },
  props: ["data", "content_language", "existing_record"],
  data() {
    return {
      form: this.$inertia.form(this.prepareForm()),
    };
  },
  watch: {
    "form.title": {
      handler(newValue, oldValue) {
        // If auto slug generation is not disabled
        if (!this.form.disable_auto_slug_gen) {
          this.createSlug(newValue);
        }
      },
      deep: true,
    },
  },
  methods: {
    prepareForm() {
      let inputs = {
        disable_auto_slug_gen: false,
        name: null,
        title: null,
        slug: null,
        sub_title: null,
        image: null,
        image_position: null,
        image_alt_text: null,
        content: null,
        meta_tags: null,
        appearance: {
          bg_color: null,
          text_color: null,
          title_alignment: null,
          image_alignment: null,
          header_minimum_height: null,
        },
      };
      if (this.existing_record) {
        inputs = { ...inputs, ...this.existing_record };
      }
      return inputs;
    },
    createSlug(newValue) {
      if (newValue) {
        // Remove spaces after the last word
        let text = newValue.replace(/\s+$/, "");
        // Create the slug
        this.form.slug = text
          .toLowerCase()
          .replace(/ /g, "-")
          .replace(/[^\w-]+/g, "");
      } else {
        this.form.slug = null;
      }
    },
  },
};
</script>