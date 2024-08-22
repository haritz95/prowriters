<template>
  <SettingsLayout :title="data.title">
    <form @submit.prevent="form.post(route('admin.settings.social.login.update'))">
      <ActionToolBar
        :disable_save_button="form.processing"
        :toolbar="toolbar"
      />     

      <Input
        v-model="form.google_client_id"
        name="google_client_id"
        :label="__('Google Client Key')"
      />
      <Input
        v-model="form.google_client_secret"
        name="google_client_secret"
        :label="__('Google Client Secret')"
      />

      <CheckBox
        v-model="form.is_google_enable"
        :label="__('Enable Login with Google')"
        name="is_google_enable"
      />

      <div class="mt-4">
        <label>{{ __("Callback URL") }}</label>
        <div class="text-muted">{{ data.call_back_urls.google }}</div>
      </div>
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