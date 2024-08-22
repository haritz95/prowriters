<template>
  <SettingsLayout :title="data.title">
    <form
      @submit.prevent="form.patch(route('admin.settings.websiteUi.update'))"
    >
      <ActionToolBar
        :disable_save_button="form.processing"
        :toolbar="toolbar"
      />

      <Fieldset :title="__('Top Navigation')">
        <ColorPicker
          v-model="form.website_top_nav_bg_color"
          :label="__('Background color of top menu')"
          name="website_top_nav_bg_color"
        />

        <ColorPicker
          v-model="form.website_top_nav_link_color"
          :label="__('Link color of top menu')"
          name="website_top_nav_link_color"
        />

        <ColorPicker
          v-model="form.website_top_nav_link_hover_color"
          :label="__('Link Hover color of top menu')"
          name="website_top_nav_link_hover_color"
        />

        <ColorPicker
          v-model="form.website_order_now_button_bg_color"
          :label="__('Order now button background color on top menu')"
          name="website_order_now_button_bg_color"
        />

        <ColorPicker
          v-model="form.website_order_now_button_hover_bg_color"
          :label="__('Order now button hover background color on top menu')"
          name="website_order_now_button_hover_bg_color"
        />

        <ColorPicker
          v-model="form.website_order_now_button_text_color"
          :label="__('Order now button text color on top menu')"
          name="website_order_now_button_text_color"
        />
      </Fieldset>

      <Fieldset :title="__('Homepage Hero Section')">
        <div class="row">
          <div class="col-md-6">
            <Select
              v-model="form.website_hero_image_position"
              :options="data.dropdowns.image_positions"
              :label="__('Hero Image Position')"
              name="website_hero_image_position"
            />
          </div>
          <div class="col-md-6">
            <FileChooser
              v-model="form.website_hero_image"
              :label="__('Hero Image')"
              name="website_hero_image"
            />
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <ColorPicker
              v-model="form.website_hero_bg_color"
              :label="__('Hero Background Color')"
              name="website_hero_bg_color"
            />
          </div>
          <div class="col-md-6">
            <ColorPicker
              v-model="form.website_hero_text_color"
              :label="__('Hero Text Color')"
              name="website_hero_text_color"
            />
          </div>
        </div>
      </Fieldset>

      <Fieldset :title="__('Footer')">
        <ColorPicker
          v-model="form.website_footer_bg_color"
          :label="__('Background color of footer section')"
          name="website_footer_bg_color"
        />

        <ColorPicker
          v-model="form.website_footer_text_color"
          :label="__('Text color of footer section')"
          name="website_footer_text_color"
        />

        <ColorPicker
          v-model="form.website_footer_link_color"
          :label="__('Link color of footer section')"
          name="website_footer_link_color"
        />

        <ColorPicker
          v-model="form.website_footer_link_hover_color"
          :label="__('Link Hover color of footer section')"
          name="website_footer_link_hover_color"
        />
      </Fieldset>

      <Fieldset :title="__('Links')">
        <div class="row">
          <div class="col-md-6">
            <ColorPicker
              v-model="form.website_link_color"
              :label="__('Link Color')"
              name="website_link_color"
            />
          </div>
          <div class="col-md-6">
            <ColorPicker
              v-model="form.website_link_hover_color"
              :label="__('Link Hover Color')"
              name="website_link_hover_color"
            />
          </div>
        </div>
      </Fieldset>

      <FileChooser
        v-model="form.website_logo"
        :label="__('Website Logo')"
        name="website_logo"
      />

      <FileChooser
        v-model="form.website_favicon"
        :label="__('Website Favicon')"
        name="website_favicon"
      />

      <FileChooser
        v-model="form.website_accept_payment_image"
        :label="__('Image for - We accept payments via section')"
        name="website_accept_payment_image"
      />
    </form>
  </SettingsLayout>
</template>
    
    <script>
import SettingsLayout from "./Partials/SettingsLayout.vue";
import ActionToolBar from "./Partials/ActionToolBar.vue";

import {
  Input,
  Select,
  TextArea,
  SubmitButton,
  FileChooser,
  ColorPicker,
} from "../../../components/Form/Index.js";

import Fieldset from "../../../components/Fieldset.vue";

export default {
  props: ["data", "records"],
  components: {
    SettingsLayout,
    ActionToolBar,
    Input,
    Select,
    TextArea,
    SubmitButton,
    FileChooser,
    ColorPicker,
    Fieldset,
  },
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