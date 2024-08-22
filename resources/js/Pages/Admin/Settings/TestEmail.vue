<template>
  <SettingsLayout :title="data.title">
    <form @submit.prevent="form.post(data.urls.submit_form, formConfig)">
      <ActionToolBar :toolbar="toolbar" />
      <Input
        v-model="form.email"
        name="email"
        :label="__('Enter an email address to test the email configuration')"
        :required="true"
      />
      <SubmitButton
        :disabled="form.processing"
        :button_text="__('Send Email')"
      />
    </form>
  </SettingsLayout>
</template>

<script>
import SettingsLayout from "./Partials/SettingsLayout.vue";
import ActionToolBar from "./Partials/ActionToolBar.vue";
import { Input, SubmitButton } from "../../../components/Form/Index.js";

export default {
  components: {
    SettingsLayout,
    ActionToolBar,
    Input,
    SubmitButton,
  },
  props: ["data"],
  data() {
    return {
      form: this.$inertia.form({
        email: "",
      }),
      formConfig: {
        preserveScroll: false,
        onSuccess: () => this.form.reset(),
      },
      toolbar: {
        title: this.data.title,
        hide_save_button: true,
        links: {
          previous_page: {
            title: this.data.previous_page_title,
            url: this.data.urls.previous_page,
          },
        },
      },
    };
  },
};
</script>