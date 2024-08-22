<template>
  <AppHead :title="data.title" />
  <ManageContentLayout :content_language="content_language" :title="data.title">
    <form
      @submit.prevent="
        form.patch(
          route(
            'admin.manage.content.homepage.section.footer.update',
            content_language
          )
        )
      "
    >
      <TextArea
        v-model="form.additional_data.company_information"
        :label="__('Company Information')"
        name="additional_data.company_information"
        :required="true"
      />

      <Input
        v-model="form.additional_data.footer_text"
        :label="__('Footer Text')"
        name="additional_data.footer_text"        
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
        additional_data: {
          company_information: null,
          footer_text: null,
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