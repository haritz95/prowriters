<template>
  <Modal :title="data.title" size="small">
    <form @submit.prevent="form.post(data.urls.submit_form)">
      <div class="row">
        <div class="col-md-6">
          <Input
            v-model="form.first_name"
            name="first_name"
            :label="__('First Name')"
            :required="true"
          />
        </div>
        <div class="col-md-6">
          <Input
            v-model="form.last_name"
            name="last_name"
            :label="__('Last Name')"
            :required="true"
          />
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <Input
            v-model="form.email"
            name="email"
            :label="__('Email')"
            :required="true"
          />
        </div>

        <div class="col-md-6">
          <Input
            v-model="form.password"
            name="password"
            :label="__('Password')"
            :required="true"
          />
        </div>
      </div>

      <Select
        :searchable="true"
        :reduce_key="'code'"
        :clearable="true"
        :options="data.dropdowns.countries"
        v-model="form.country_code"
        :label="__('Country')"
        :required="true"
        name="country"
      />

      <Select
        :searchable="true"
        :options="data.dropdowns.timezones"
        v-model="form.timezone"
        :label="__('Time Zone')"
        :required="true"
        name="timezone"
      />

      <Phone v-model="form.phone" name="phone" :label="__('Phone')" />

      <fieldset class="border rounded-3 p-3">
        <legend class="float-none w-auto px-3 fs-8">
          {{ __("Role") }}
        </legend>

        <Radio :options="data.roles" v-model="form.role" name="role" />
      </fieldset>

      <div class="mt-4">
        <CheckBox
          v-model="form.send_notification_email"
          name="send_notification_email"
          :label="__('Send notification email with password')"
        />
      </div>

      <SubmitButton class="mt-4" :disabled="form.processing" />
    </form>
  </Modal>
</template>

<script>
import {
  Input,
  Select,
  CheckBox,
  Phone,
  Radio,
  SubmitButton,
} from "../../../components/Form/Index.js";
export default {
  props: ["data"],
  components: {
    Input,
    Select,
    CheckBox,
    Radio,
    Phone,
    SubmitButton,
  },
  data() {
    return {
      form: this.$inertia.form({
        first_name: null,
        last_name: null,
        email: null,
        password: Math.random().toString(36).slice(2),
        phone: null,
        timezone: null,
        country_code: null,
        send_notification_email: null,
        role: null,
        // permission_list: this.initializePermissionList(),
      }),
    };
  },
  // methods: {
  //   initializePermissionList() {
  //     let permissions = JSON.parse(JSON.stringify(this.data.permission_names));
  //     permissions["is_super_admin"] = null;
  //     Object.keys(permissions).forEach(function (name, index) {
  //       permissions[name] = null;
  //     });
  //     return permissions;
  //   },
  // },
};
</script>