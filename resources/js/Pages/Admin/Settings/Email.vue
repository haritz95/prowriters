<template>
  <SettingsLayout :title="data.title">
    <form @submit.prevent="form.post(data.urls.submit_form)">
      <ActionToolBar
        :disable_save_button="form.processing"
        :toolbar="toolbar"
        @submit="submitForm"
      />

      <div class="row">
        <div class="col-md-6">
          <Select
            v-model="form.mail_mailer"
            :options="data.dropdowns.email_sending_options"
            name="mail_mailer"
            :label="__('Send email using')"
            :required="true"
          />
        </div>
        <div class="col-md-6">
          <Select
            v-if="form.mail_mailer != 'log'"
            v-model="form.queue_connection"
            :options="data.dropdowns.queue_connection_options"
            name="queue_connection"
            :label="__('Queue Connection')"
            :required="true"
          />
        </div>
      </div>

      <Input
        v-if="form.mail_mailer != 'log'"
        v-model="form.mail_from_address"
        name="mail_from_address"
        :label="__('Email from address')"
        :required="true"
      />

      <div v-if="form.mail_mailer == 'mailgun'">
        <div class="row">
          <div class="col-md-6">
            <Input
              v-model="form.mailgun_domain"
              name="mailgun_domain"
              :label="__('Mailgun Domain')"
              :required="true"
            />
          </div>
          <div class="col-md-6">
            <Input
              v-model="form.mailgun_secret"
              name="mailgun_secret"
              :label="__('Mailgun Key')"
              :required="true"
            />
          </div>
        </div>
      </div>

      <div v-if="form.mail_mailer == 'smtp'">
        <div class="row">
          <div class="col-md-6">
            <Input
              v-model="form.mail_host"
              name="mail_host"
              :label="__('SMTP Host')"
              :required="true"
            />
          </div>
          <div class="col-md-3">
            <Input
              v-model="form.mail_port"
              name="mail_port"
              :label="__('SMTP Port')"
              :required="true"
            />
          </div>
          <div class="col-md-3">
            <Select
              v-model="form.mail_encryption"
              :options="data.dropdowns.email_encryptions"
              name="mail_encryption"
              :label="__('Email Encryption')"
              :required="true"
            />
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <Input
              v-model="form.mail_username"
              name="mail_username"
              :label="__('SMTP Username')"
              :tooltip="data.tooltips.smtp_username"
            />
          </div>
          <div class="col-md-6">
            <Input
              v-model="form.mail_password"
              name="mail_password"
              :label="__('SMTP Password')"
              :required="true"
            />
          </div>
        </div>
      </div>
    </form>
  </SettingsLayout>
</template>

<script>
import SettingsLayout from "./Partials/SettingsLayout.vue";
import ActionToolBar from "./Partials/ActionToolBar.vue";
import Input from "../../../components/Form/Input.vue";
import Select from "../../../components/Form/Select.vue";

export default {
  components: {
    SettingsLayout,
    ActionToolBar,
    Input,
    Select,
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