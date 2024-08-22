<template>
  <AppHead :title="data.title" />
  <ManageContentLayout :content_language="content_language" :title="data.title">
    <template v-slot:action>
      <div class="d-grid gap-2">
      <Link
        class="btn btn-sm btn-light"
        :href="route('admin.manage.content.posts.index', content_language)"
      >
      <i class="fa-solid fa-arrow-left-long"></i> {{ __("back to") }} {{ __("Articles") }}</Link
      >
    </div>
    </template>

    <form
      @submit.prevent="
        existing_record
          ? form.patch(
              route('admin.manage.content.posts.update', [
                content_language,
                existing_record.id,
              ])
            )
          : form.post(
              route('admin.manage.content.posts.store', content_language)
            )
      "
    >
      <div class="row">
        <div class="col-md-8">
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

          <Select
            :multiple="true"
            v-model="form.categories"
            :options="data.dropdowns.categories"
            name="categories"
            :label="__('Categories')"
            :required="true"
          />

          <RichEditor
            v-model="form.content"
            name="content"
            :label="__('Content')"
            :required="true"
          />

          <TextArea
            v-model="form.excerpt"
            name="excerpt"
            :label="__('Excerpt')"
            :required="true"
          />
          <TextArea
            v-model="form.meta_tags"
            name="meta_tags"
            :label="__('Meta Tags')"
            :required="true"            
          />

          <Input
            v-model="form.author_name"
            name="author_name"
            :label="__('Author Name')"
            :required="true"
          />
        </div>
        <div class="col-md-4">
          <div class="d-grid gap-2 mb-4">
            <SubmitButton           
              :disabled="form.processing"
            />
          </div>

          <CheckBox
            v-model="form.disable_auto_slug_gen"
            name="disable_auto_slug_gen"
            :label="__('Disable Auto Slug Generation')"
          />

          <CheckBox
            v-model="form.published"
            name="published"
            :label="__('Published')"
          />

          <fieldset class="border rounded-3 p-3 mb-4">
            <legend class="float-none w-auto px-3 fs-8">
              {{ __("Thumbnail Image") }}
            </legend>
            <div style="height: 150px" class="w-100 border bg-light">
              <img
                style="max-height: 150px"
                :src="form.thumbnail_image"
                class="img-fluid h-100"
                alt=""
              />
            </div>
            <button
              v-if="form.thumbnail_image"
              type="button"
              class="btn btn-link"
              @click="form.thumbnail_image = null"
            >
              {{ __("Clear") }}
            </button>

            <FileChooser
              v-if="!form.thumbnail_image"
              v-model="form.thumbnail_image"
              name="thumbnail_image"
            />
            <Input
              v-model="form.thumbnail_image_alt_title"
              name="thumbnail_image_alt_title"
              :label="__('ALT Title')"
              :required="true"
            />
          </fieldset>

          <fieldset class="border rounded-3 p-3 mb-4">
            <legend class="float-none w-auto px-3 fs-8">
              {{ __("Cover Image") }}
            </legend>

            <div style="height: 150px" class="w-100 border bg-light">
              <img
                style="max-height: 150px"
                :src="form.cover_image"
                class="img-fluid h-100"
                alt=""
              />
            </div>
            <button
              v-if="form.cover_image"
              type="button"
              class="btn btn-link"
              @click="form.cover_image = null"
            >
              {{ __("Clear") }}
            </button>
            <FileChooser
              v-if="!form.cover_image"
              v-model="form.cover_image"
              name="cover_image"
            />

            <Input
              v-model="form.cover_image_alt_title"
              name="cover_image_alt_title"
              :label="__('ALT Title')"
              :required="true"
            />
          </fieldset>
        </div>
      </div>
    </form>
  </ManageContentLayout>
</template>

<script>
import ManageContentLayout from "../../Partials/ManageContentLayout.vue";
import {
  Input,
  TextArea,
  Select,
  CheckBox,
  FileChooser,
  SubmitButton,
} from "../../../../../components/Form/Index.js";

import RichEditor from "../../../../../components/RichEditor.vue";

export default {
  components: {
    ManageContentLayout,
    Input,
    TextArea,
    Select,
    CheckBox,
    RichEditor,
    FileChooser,
    SubmitButton,
  },
  props: ["data", "content_language", "existing_record"],
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
  data() {
    return {
      form: this.$inertia.form(this.prepareForm()),
    };
  },
  methods: {
    prepareForm() {
      let inputs = {
        disable_auto_slug_gen: false,
        slug: null,
        title: null,
        author_name: null,
        thumbnail_image: null,
        thumbnail_image_alt_title: null,
        cover_image: null,
        cover_image_alt_title: null,
        excerpt: null,
        content: null,
        meta_tags: null,
        published: null,
        disable_auto_slug_gen: null,
        categories: null,
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