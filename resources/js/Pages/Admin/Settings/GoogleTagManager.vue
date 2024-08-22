<template>
  <SettingsLayout :title="data.title">
    <form @submit.prevent="form.post(route('admin.settings.googleTagManager.update'))">
      <ActionToolBar
        :disable_save_button="form.processing"
        :toolbar="toolbar"
      />
      <Input
        v-model="form.google_tag_id"
        name="google_tag_id"
        :label="__('Google Tag Manager ID')"  
        :placeholder="__('Example: UA-34294382-6')"      
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