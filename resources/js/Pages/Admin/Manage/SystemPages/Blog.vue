<template>
  <AppHead :title="data.title" />
  <ManageContentLayout :content_language="content_language" :title="data.title">
    <form
      @submit.prevent="
        form.patch(
          route(
            'admin.manage.content.systemPages.blog.update',
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
        :required="true"
      />

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
  SubmitButton,
} from "../../../../components/Form/Index.js";
export default {
  props: ["data", "existing_record", "content_language"],
  components: {
    ManageContentLayout,
    Input,
    SubmitButton,
    TextArea,
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