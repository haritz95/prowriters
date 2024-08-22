<template>
  <SettingsLayout :title="data.title">
    <ActionToolBar :disable_save_button="form.processing" :toolbar="toolbar" />

    <form
      @submit.prevent="
        existing_record
          ? form.patch(data.urls.submit_form)
          : form.post(data.urls.submit_form)
      "
    >
      <Input
        v-model="form.name"
        name="name"
        :label="__('Name')"
        :required="true"
      />
      <Input
        v-model="form.description"
        name="description"
        :label="__('Description')"
        :required="true"
      />
      <Input
        v-model="form.success_message"
        name="success_message"
        :label="__('Message to display after submitting the payment')"
        :required="true"
      />

      <TextArea
        :rows="2"
        v-model="form.instruction"
        name="instruction"
        :label="__('Instruction to customer')"
        :note="__('(e.g bank name, account number, swift code etc.)')"
      />

      <CheckBox
        v-model="form.settings.requires_transaction_number"
        :label="__('Requires Evidence') + ' / ' + __('Transaction Number')"
        name="settings.requires_transaction_number"
      />
      <Input
        v-if="form.settings.requires_transaction_number"
        v-model="form.settings.reference_field_label"
        name="settings.reference_field_label"
        :label="__('Field name to display for entering transaction number')"
        :required="true"
      />

      <CheckBox
        v-model="form.settings.requires_uploading_attachment"
        :label="__('Requires Uploading Attachment')"
        name="settings.requires_uploading_attachment"
      />
      <Input
        v-if="form.settings.requires_uploading_attachment"
        v-model="form.settings.attachment_field_label"
        name="settings.attachment_field_label"
        :label="__('Field name to display for attachment uploading')"
        :required="true"
      />

      <CheckBox
        v-model="form.inactive"
        :label="__('Inactive')"
        name="inactive"
      />

      <SubmitButton :disabled="form.processing" />
    </form>
  </SettingsLayout>
</template>

<script>
import SettingsLayout from "../../Partials/SettingsLayout.vue";
import ActionToolBar from "../../Partials/ActionToolBar.vue";
import {
  Input,
  TextArea,
  CheckBox,
  SubmitButton,
} from "../../../../../components/Form/Index.js";

export default {
  components: {
    SettingsLayout,
    ActionToolBar,
    Input,
    TextArea,
    CheckBox,
    SubmitButton,
  },
  props: ["data", "existing_record"],
  data() {
    return {
      form: this.$inertia.form(this.prepareForm()),
      toolbar: {
        title: this.data.title,
        links: {
          previous_page: {
            title: this.data.previous_link_text,
            url: this.data.urls.previous_page,
          },
        },
        hide_save_button: true,
      },
    };
  },
  methods: {
    prepareForm() {
      let inputs = {
        name: null,
        description: null,
        instruction: null,
        settings: {
          requires_transaction_number: false,
          reference_field_label: null,
          requires_uploading_attachment: false,
          attachment_field_label: null,
        },
        success_message: null,
        inactive: null,
      };
      if (this.existing_record) {
        inputs = { ...inputs, ...this.existing_record };
      }
      return inputs;
    },
  },
};
</script>