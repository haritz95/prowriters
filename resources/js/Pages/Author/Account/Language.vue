<template>
  <AppHead :title="data.title" />
  <AccountLayout :author="user">
    <div class="card">
      <div class="card-header h5">
        {{ data.title }}
      </div>
      <div class="card-body">
        <form
          @submit.prevent="
            form.patch(route('author.account.language.update'), formConfig)
          "
        >
          <Select
            :options="data.dropdowns.languages"
            :reduce_key="'iso_code'"
            v-model="form.language"
            :label="__('Language')"
            :required="true"
            name="language"
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
        language: this.user.language,
      }),
      formConfig: {
        preserveScroll: false,
        onSuccess: () => window.location.reload(),
      },
    };
  },
};
</script>
