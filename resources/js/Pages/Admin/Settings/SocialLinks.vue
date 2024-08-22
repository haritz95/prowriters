<template>
  <SettingsLayout :title="data.title">
    <form
      @submit.prevent="form.post(route('admin.settings.social.links.update'))"
    >
      <ActionToolBar
        :disable_save_button="form.processing"
        :toolbar="toolbar"
      />

      <Input v-model="form.facebook" name="facebook" :label="__('Facebook')" />
      <Input v-model="form.twitter" name="twitter" :label="__('Twitter')" />

      <Input
        v-model="form.instagram"
        :label="__('Instagram')"
        name="instagram"
      />

      <Input v-model="form.linkedin" :label="__('Linkedin')" name="linkedin" />

      <Input v-model="form.youtube" :label="__('Youtube')" name="youtube" />
    </form>
  </SettingsLayout>
</template>
   
   <script>
import SettingsLayout from "./Partials/SettingsLayout.vue";
import ActionToolBar from "./Partials/ActionToolBar.vue";
import { Input } from "../../../components/Form/Index.js";

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