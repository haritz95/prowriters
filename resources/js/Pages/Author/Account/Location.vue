<template>
  <AppHead :title="data.title" />
  <AccountLayout :author="author">
    <div class="card">
      <div class="card-header h5">
        {{ data.title }}
      </div>
      <div class="card-body">
        <form @submit.prevent="form.patch(data.urls.submit_form, formConfig)">
          <TextArea
            v-model="form.address"
            :label="__('Address')"
            name="address"
          />
          <div class="row">
            <div class="col">
              <Input v-model="form.city" :label="__('City')" name="city" />
            </div>
            <div class="col">
              <Input v-model="form.state" :label="__('State')" name="state" />
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
          <SubmitButton :disabled="form.disabled" />
        </form>
      </div>
    </div>
  </AccountLayout>
</template>

<script>
import AccountLayout from "./Partials/AccountLayout.vue";

import {
  Input,
  TextArea,
  SubmitButton,
  Phone,
  Select,
} from "../../../components/Form/Index.js";

export default {
  props: ["data", "author"],
  components: {
    AccountLayout,
    Input,
    TextArea,
    SubmitButton,
    Phone,
    Select,
  },
  data() {
    return {
      form: this.$inertia.form({
        address: this.author.profile.address,
        state: this.author.profile.state,
        city: this.author.profile.city,
        country_code: this.author.country_code,
        timezone: this.author.timezone,
      }),
      formConfig: {
        preserveScroll: false,
      },
    };
  },
};
</script>