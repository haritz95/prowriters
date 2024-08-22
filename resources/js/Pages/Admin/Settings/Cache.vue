<template>
  <SettingsLayout :title="data.title">
    <form @submit.prevent="form.post(data.urls.submit_form, formConfig)">
      <ActionToolBar :toolbar="toolbar" />   
      <SubmitButton
        :disabled="form.processing"
        :button_text="__('Clear Cache')"
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
      form: this.$inertia.form(),
      formConfig: {
        preserveScroll: false,
        onSuccess: () => this.form.reset(),
      },
      toolbar: {
        title: this.data.title,
        hide_save_button: true,    
      },
    };
  },
};
</script>