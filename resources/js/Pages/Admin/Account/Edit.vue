<template>
  <AppHead :title="data.title" />
  <AccountLayout :admin="user">
    <div class="card">
      <div class="card-header h5">
        {{ data.title }}
      </div>
      <div class="card-body">
        <form @submit.prevent="form.patch(route('admin.account.update'), formConfig)">
          <div class="row">
            <div class="col">
              <Input
                v-model="form.first_name"
                :label="__('First Name')"
                name="first_name"
                :required="true"
              />
            </div>
            <div class="col">
              <Input
                v-model="form.last_name"
                :label="__('Last Name')"
                name="last_name"
                :required="true"
              />
            </div>
          </div>
          <Input
            type="email"
            v-model="form.email"
            :label="__('Email')"
            name="email"
            :required="true"
          />
          <Phone v-model="form.phone" :label="__('Phone')" :required="true" />
          <SubmitButton :disabled="form.disabled" />
        </form>
      </div>
    </div>
  </AccountLayout>
</template>

<script>
import AccountLayout from "./Partials/AccountLayout.vue";

import { Input, TextArea, SubmitButton, Phone } from "../../../components/Form/Index.js";

export default {
  props: ["data", "user"],
  components: {
    AccountLayout,
    Input,
    TextArea,
    SubmitButton,
    Phone,
  },
  data() {
    return {
      form: this.$inertia.form({
        first_name: this.user.first_name,
        last_name: this.user.last_name,
        email: this.user.email,
        phone: this.user.phone,
      }),
      formConfig: {
        preserveScroll: false,
      },
    };
  },
};
</script>
