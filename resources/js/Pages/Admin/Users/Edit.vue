<template>
  <Modal :title="data.title">
    <form @submit.prevent="form.patch(data.urls.submit_form)">
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
      <Input
        v-model="form.email"
        name="email"
        :label="__('Email')"
        :required="true"
      />
      <Phone v-model="form.phone" name="phone" :label="__('Phone')" />

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

      <CheckBox
        v-model="form.inactive"
        name="inactive"
        :label="__('Inactive')"
      />

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
  SubmitButton,
} from "../../../components/Form/Index.js";
export default {
  props: ["data", "user"],
  components: {
    Input,
    Select,
    CheckBox,
    Phone,
    SubmitButton,
  },
  data() {
    return {
      form: this.$inertia.form(this.prepareForm()),
      formConfig: {
        preserveScroll: false,
        onSuccess: () => this.form.reset(),
      },
    };
  },
  methods: {
    prepareForm() {
      let inputs = {
        first_name: null,
        last_name: null,
        email: null,
        phone: null,
        timezone: null,
        country_code: null,
        language: null,
      };
      if (this.user) {
        inputs = { ...inputs, ...this.user };
      }
      return inputs;
    },
  },
};
</script>