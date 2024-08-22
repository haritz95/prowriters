<template>
  <Modal :title="data.title">
    <form
      @submit.prevent="
        existing_record
          ? form.patch(data.urls.submit_form)
          : form.post(data.urls.submit_form, formConfig)
      "
    >
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
        <div :class="[existing_record ? 'col-md-12' : 'col-md-6']">
          <Input
            v-model="form.email"
            name="email"
            :label="__('Email')"
            :required="true"
          />
        </div>

        <div class="col-md-6" v-if="!existing_record">
          <Input
            v-model="form.password"
            name="password"
            :label="__('Password')"
            :required="true"
          />
        </div>
      </div>

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
        v-model="form.allow_paying_later"
        name="allow_paying_later"
        :label="__('Allow Paying Later')"
        :tooltip="__('Allow customers to place order without upfront payment. You can create invoice for the tasks later.')"
      />

      <Input
        v-if="form.allow_credit"
        v-model="form.credit_limit"
        name="credit_limit"
        :label="__('Credit Limit')"
        :required="true"
        @keypress="onlyNumber($event, form.credit_limit)"
      />

      <TextArea
        v-model="form.internal_note"
        name="internal_note"
        :label="__('Internal Note')"
      />

      <CheckBox
        v-model="form.inactive"
        name="inactive"
        :label="__('Inactive')"
      />

      <SubmitButton :disabled="form.processing" />
    </form>
  </Modal>
</template>

<script>
import {
  Input,
  TextArea,
  Select,
  CheckBox,
  Phone,
  SubmitButton,
} from "../../../components/Form/Index.js";
export default {
  props: ["data", "existing_record"],
  components: {
    Input,
    TextArea,
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
        country_code: null,
        timezone: null,
        allow_paying_later: null,
        // credit_limit: null,
        internal_note: null,
        inactive: null,
        password: null,
      };

      if (this.existing_record) {
        inputs = { ...inputs, ...this.existing_record };
      }
      return inputs;
    },
  },
};
</script>