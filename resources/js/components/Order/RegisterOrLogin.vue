<template>
  <ul class="nav nav-tabs">
    <li class="nav-item">
      <a
        @click.prevent="changeCustomerType('returning_customer')"
        href="#"
        class="nav-link"
        v-bind:class="{ active: form.customer_type == 'returning_customer' }"
        >{{ __("Returning customer") }}</a
      >
    </li>
    <li class="nav-item">
      <a
        @click.prevent="changeCustomerType('new_customer')"
        href="#"
        class="nav-link"
        v-bind:class="{ active: form.customer_type == 'new_customer' }"
        >{{ __("New customer") }}</a
      >
    </li>
  </ul>
  <div class="p-2" v-if="form.customer_type == 'returning_customer'">
    <form
      @submit.prevent="form.post(route('checkout.loginOrRegister'), formConfig)"
    >
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
            type="password"
            name="password"
            :label="__('Password')"
            :required="true"
          />
        </div>
      </div>

      <SubmitButton :button_text="__('Login')" />
    </form>
  </div>

  <div class="p-2" v-if="form.customer_type == 'new_customer'">
    <form
      @submit.prevent="form.post(route('checkout.loginOrRegister'), formConfig)"
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
        <div class="col-md-6">
          <Input
            v-model="form.email"
            name="email"
            :label="__('Email')"
            :required="true"
          />
        </div>
        <div class="col-md-6">
          <Phone v-model="form.phone" :label="__('Phone')" name="phone" />
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <Input
            v-model="form.password"
            type="password"
            name="password"
            :label="__('Password')"
            :required="true"
          />
        </div>
        <div class="col-md-6">
          <Input
            v-model="form.password_confirmation"
            type="password"
            name="password_confirmation"
            :label="__('Confirm Password')"
            :required="true"
          />
        </div>
      </div>
      <SubmitButton :button_text="__('Register')" />
    </form>
  </div>

  <fieldset class="border rounded-3 p-3 mb-2">
    <legend
      class="float-none w-auto px-3"
      style="margin-left: auto; margin-right: auto"
    >
      {{ __("or") }}
    </legend>
    <div class="text-center mb-3">
      <SocialLogin :is_checkout="true" />
    </div>
  </fieldset>
</template>


<script>
import { Input, Phone, SubmitButton } from "../Form/Index.js";
import SocialLogin from "../SocialLogin.vue";
export default {
  // inject: ["form"],
  components: {
    Input,
    Phone,
    SubmitButton,
    SocialLogin,
  },
  data() {
    return {
      form: this.$inertia.form({
        customer_type: "returning_customer",
        first_name: "",
        last_name: "",
        email: "",
        password: "",
        password_confirmation: "",
        phone: "",
        coupon_code: "",
      }),
      formConfig: {
        preserveScroll: false,
        // onSuccess: () => this.form.reset(),
      },
      bindProps: {
        placeholder: this.__("Enter your phone number"),
        inputClasses: "form-control",
      },
    };
  },

  methods: {
    changeCustomerType(type) {
      this.form.customer_type = type;
    },
  },
};
</script>
