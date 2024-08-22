<template>
  <SettingsLayout :title="data.title">
    <form @submit.prevent="form.post(route('admin.settings.recaptcha.update'))">
      <ActionToolBar
        :disable_save_button="form.processing"
        :toolbar="toolbar"
      />
      <CheckBox
        v-model="form.recaptcha_enable"
        :label="__('Enable Recaptcha')"
        name="recaptcha_enable"
      />

      <Input
        v-model="form.recaptcha_site_key"
        name="recaptcha_site_key"
        :label="__('Recaptcha Site Key')"
        :required="true"
      />
      <Input
        v-model="form.recaptcha_secret"
        name="recaptcha_secret"
        :label="__('Recaptcha Secret')"
        :required="true"
      />
    </form>
  </SettingsLayout>
</template>
 
 <script>
import SettingsLayout from "./Partials/SettingsLayout.vue";
import ActionToolBar from "./Partials/ActionToolBar.vue";
import { Input, CheckBox } from "../../../components/Form/Index.js";

export default {
  components: {
    SettingsLayout,
    ActionToolBar,
    Input,
    CheckBox,
  },
  props: ["data", "records"],
  data() {
    return {
      form: this.$inertia.form(this.records),
      toolbar: {
        title: this.data.title,
      },
    };
  },
};
</script>