<template>
 <AppHead :title="data.page.additional_data.meta_title">
    <meta
      name="description"
      :content="data.page.additional_data.meta_description"
    />
    <meta name="keywords" :content="data.page.additional_data.meta_keywords" />
    <meta property="og:title" :content="data.page.additional_data.meta_title" />
    <meta
      property="og:image"
      :content="asset(data.page.additional_data.meta_image)"
    />
  </AppHead>

   <AuthLayout :data="data.page.additional_data">
    <div class="p-5">
      <h6 class="h3">{{ data.page.title }}</h6>
      <p class="text-muted mb-0">
        
        {{ data.page.sub_title }}
        
      </p>      
      <hr />
      <form @submit.prevent="form.post(route('register'))" autocomplete="off">
        <div class="mb-3">
          <label class="form-label">{{ __("First Name") }}</label>
          <input
            v-model="form.first_name"
            type="text"
            class="form-control shadow-none"
            required
            autocomplete="first_name"
            :placeholder="__('First Name')"
            autofocus
            @focus="handleInputFocus('first_name')"
          />
          <ValidationError name="first_name" />
        </div>

        <div class="mb-3">
          <label class="form-label">{{ __("Last Name") }}</label>
          <input
            v-model="form.last_name"
            type="text"
            class="form-control shadow-none"
            required
            autocomplete="last_name"
            :placeholder="__('Last Name')"
            @focus="handleInputFocus('last_name')"
          />
          <ValidationError name="last_name" />
        </div>

        <div class="mb-3">
          <label class="form-label">{{ __("Email") }}</label>
          <div class="input-group">
            <span class="input-group-text" id="email"
              ><i class="far fa-user"></i
            ></span>
            <input
              v-model="form.email"
              type="email"
              class="form-control shadow-none"
              required
              autocomplete="email"
              :placeholder="__('Email')"
              @focus="handleInputFocus('email')"
            />
          </div>
          <ValidationError name="email" />
        </div>
        <div class="mb-3">
          <label class="form-label"
            >{{ __("Password") }}
            <i
              class="fa-solid fa-circle-question"
              data-bs-toggle="tooltip"
              data-bs-placement="top"
              :data-bs-title="password_tooltip_title"
            ></i>
          </label>

          <div class="input-group">
            <span class="input-group-text" id="password"
              ><i class="fas fa-key"></i
            ></span>
            <input
              v-model="form.password"
              :type="password_visibility ? 'text' : 'password'"
              class="form-control shadow-none"
              :placeholder="__('Password')"
              required
              @focus="handleInputFocus('password')"
            />
            <span
              type="button"
              class="input-group-text"
              @click="password_visibility = !password_visibility"
            >
              <i class="fa-regular fa-eye" v-if="password_visibility"></i>
              <i class="fa-regular fa-eye-slash" v-else></i>
            </span>
          </div>
          <ValidationError name="password" />
        </div>

        <Phone v-model="form.phone" :label="__('Phone')" name="phone" />

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

        <div class="mb-3">
          <button
            :disabled="form.processing || (!form.recaptcha)"
            type="submit"
            class="btn btn-primary btn-block"
          >
            {{ __("Create my account") }}
            <i class="fa-solid fa-arrow-right-long"></i>
          </button>
        </div>
      </form>

      <div class="mt-4">{{ __('Already have an account?')  }} <Link :href="route('login')">{{ __("Login") }}</Link> </div>

    </div>
  </AuthLayout>
</template>
  
  <script>
import AuthLayout from "./Partials/AuthLayout.vue";
import { Input, Select, Phone } from "../../components/Form/Index.js";
import { useGoogleCaptcha } from "../../composable/Recaptcha.js";

export default {
  props: ["data"],
  components: {
    AuthLayout,
    Input,
    Select,
    Phone,
  },
  mounted() {
    let scope = this;
    axios.post("https://ipinfo.io").then(function (res) {
      scope.form.country_code = res.data.country;
    });
  },
  data() {
    return {
      password_visibility: false,
      password_tooltip_title: this.data.tooltips.password,
      form: this.$inertia.form({
        first_name: null,
        last_name: null,
        email: null,
        password: null,
        phone: null,
        country_code: null,
        timezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
        recaptcha: useGoogleCaptcha().token,
      }),
    };
  },
  methods: {
    handleInputFocus(field) {
      this.$page.props.errors[field] = null;
    },
  },
};
</script>
  
  <style>
.input-group-text {
  color: #aaa;
  background-color: #fff;
  border: 1px solid #aaa;
}
</style>