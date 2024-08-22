<template>
  <SettingsLayout :title="data.title">
    <form @submit.prevent="form.post(route('admin.settings.appSettings.update'))">
      <ActionToolBar :disable_save_button="form.processing" :toolbar="toolbar" />
      <FileChooser v-model="form.company_logo" name="company_logo" :label="__('Logo')" :tooltip="__('Ideal size 154x36')" />

      <Input v-model="form.tinymce_key" name="tinymce_key" :label="__('Tinymce Key')" />

      <CheckBox
        v-model="form.disable_author_application"
        :label="__('Disable Author Application')"
        name="disable_author_application"
      />

      <CheckBox
        v-model="form.disable_order_page_for_unauthenticated_user"
        :label="__('Make order page restricted to authenticated users only')"
        name="disable_order_page_for_unauthenticated_user"
      />
      
      <CheckBox
        v-model="form.filter_contact_info_from_message"
        :label="__('Don\'t allow sending contact information in messages')"
        name="filter_contact_info_from_message"
      />

      <Fieldset :title="__('Open AI Content Generator')">
        <Input v-model="form.open_ai_api_key" name="open_ai_api_key" :label="__('API Key')" />
      </Fieldset>

      <div class="text-muted mt-5 mb-4"><span class="text-danger">***</span> {{ __('Reload the page after saving to see the changes') }}</div>

      <Fieldset :title="__('Top Navigation')">
        <ColorPicker
          v-model="form.application_top_nav_bg_color"
          :label="__('Background color of top menu')"
          name="application_top_nav_bg_color"
        />

        <!-- <ColorPicker
          v-model="form.application_top_nav_link_color"
          :label="__('Link color of top menu')"
          name="application_top_nav_link_color"
        />

        <ColorPicker
          v-model="form.application_top_nav_link_hover_color"
          :label="__('Link Hover color of top menu')"
          name="application_top_nav_link_hover_color"
        /> -->
      </Fieldset>

      <Fieldset :title="__('Links')">
        <div class="row">
          <div class="col-md-6">
            <ColorPicker
              v-model="form.application_link_color"
              :label="__('Link Color')"
              name="application_link_color"
            />
          </div>
          <div class="col-md-6">
            <ColorPicker
              v-model="form.application_link_hover_color"
              :label="__('Link Hover Color')"
              name="application_link_hover_color"
            />
          </div>
        </div>
      </Fieldset>
    </form>
  </SettingsLayout>
</template>

<script>
import SettingsLayout from "./Partials/SettingsLayout.vue";
import ActionToolBar from "./Partials/ActionToolBar.vue";
import Fieldset from "../../../components/Fieldset.vue";
import {
  FileChooser,
  Input,
  CheckBox,
  ColorPicker,
} from "../../../components/Form/Index.js";

export default {
  components: {
    SettingsLayout,
    ActionToolBar,
    FileChooser,
    Input,
    CheckBox,
    ColorPicker,
    Fieldset,
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
