<template>
  <AppHead :title="data.title" />
  <AccountLayout :customer="user">
    <div class="card">
      <div class="card-header h5">
        {{ data.title }}
      </div>
      <div class="card-body">
        <form
          @submit.prevent="form.patch(route('customer.account.location.update'), formConfig)"
        >
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

import { SubmitButton, Select } from "../../../components/Form/Index.js";

export default {
  props: ["data", "user"],
  components: {
    AccountLayout,
    SubmitButton,
    Select,
  },
  data() {
    return {
      form: this.$inertia.form({
        country_code: this.user.country_code,
        timezone: this.user.timezone,
      }),
      formConfig: {
        preserveScroll: false,
      },
    };
  },
};
</script>
