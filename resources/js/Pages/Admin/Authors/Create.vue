<template>
  <Modal :title="data.title" size="small">
    <form @submit.prevent="form.post(data.urls.submit_form, formConfig)">
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

      <Input
        type="text"
        v-model="form.password"
        :label="__('Password')"
        name="password"
        :required="true"
      />

      <Select
        :searchable="true"
        :options="data.dropdowns.timezones"
        v-model="form.timezone"
        :label="__('Timezone')"
        :required="true"
        name="timezone"
      />

      <Phone v-model="form.phone" :label="__('Phone')" name="phone" />

      <div class="w-100 p-2 bg-light mb-2 h5">{{ __("Location") }}</div>
      <div class="p-2">
        <TextArea
          v-model="form.address"
          :label="__('Address')"
          name="address"
        />
        <div class="row">
          <div class="col-md-6">
            <Input v-model="form.city" :label="__('City')" name="city" />
          </div>
          <div class="col-md-6">
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
      </div>

      <div class="w-100 p-2 bg-light mb-2 h5">
        {{ __("Experience & Skill") }}
      </div>
      <div class="p-2">
        <div class="row">
          <div class="col-md-6">
            <Select
              :searchable="true"
              :clearable="true"
              :options="data.dropdowns.education_levels"
              v-model="form.education_level_id"
              :label="__('Level of Education')"
              :required="true"
              name="education_level_id"
            />
          </div>
          <div class="col-md-6">
            <Input
              type="number"
              v-model="form.years_of_experience"
              :label="__('Years of Experience')"
              name="years_of_experience"
            />
          </div>
        </div>

        <Select
          :searchable="true"
          :clearable="true"
          :options="data.dropdowns.author_levels"
          v-model="form.author_level_id"
          :label="__('Author Level')"
          :required="true"
          name="author_level_id"
        />

        <Select
          :searchable="true"
          :clearable="false"
          :options="data.dropdowns.services"
          v-model="form.service_id_1"
          :label="__('Service 1')"
          :required="true"
          name="service_id_1"
        />

        <Select
          :searchable="true"
          :clearable="false"
          :options="data.dropdowns.services"
          v-model="form.service_id_2"
          :label="__('Service 2')"
          name="service_id_2"
        />

        <Select
          :searchable="true"
          :clearable="false"
          :options="data.dropdowns.services"
          v-model="form.service_id_3"
          :label="__('Service 3')"
          name="service_id_3"
        />

        <Select
          :searchable="true"
          :clearable="false"
          :options="data.dropdowns.subjects"
          v-model="form.subject_id_1"
          :label="__('Subject 1')"
          :required="true"
          name="subject_id_1"
        />

        <Select
          :searchable="true"
          :clearable="true"
          :options="data.dropdowns.subjects"
          v-model="form.subject_id_2"
          :label="__('Subject 2')"
          name="subject_id_2"
        />
        <Select
          :searchable="true"
          :clearable="true"
          :options="data.dropdowns.subjects"
          v-model="form.subject_id_3"
          :label="__('Subject 3')"
          name="subject_id_3"
        />
        <Select
          :searchable="true"
          :clearable="true"
          :options="data.dropdowns.subjects"
          v-model="form.subject_id_4"
          :label="__('Subject 4')"
          name="subject_id_4"
        />
        <Select
          :searchable="true"
          :clearable="true"
          :options="data.dropdowns.subjects"
          v-model="form.subject_id_5"
          :label="__('Subject 5')"
          name="subject_id_5"
        />
      </div>

      <div class="w-100 p-2 bg-light mb-2 h5">
        {{ __("Payment Settings") }}
      </div>
      <div class="p-2">
        <Input
          v-model="form.payment_method"
          :label="__('Preferred method for receiving payment')"
          name="payment_method"
          :required="true"
          :placeholder="__('e.g. Paypal, Stripe, etc.')"
        />
        <Input
          v-model="form.payment_method_details"
          :label="__('Payment method details')"
          name="payment_method_details"
          :required="true"
          :placeholder="__('e.g. Email for your Paypal account')"
        />
      </div>

      <div class="w-100 p-2 bg-light mb-2 h5">{{ __("Links") }}</div>
      <div class="p-2">
        <Input
          v-model="form.blog_url"
          :label="__('Blog URL')"
          name="blog_url"
        />
        <Input
          v-model="form.online_portfolio_url"
          :label="__('Online Portfolio URL')"
          name="online_portfolio_url"
        />
        <Input
          v-model="form.linked_in_url"
          :label="__('Linkedin Public Profile URL')"
          name="linked_in_url"
        />
      </div>

      <TextArea v-model="form.bio" :label="__('Career summary')" name="bio" />

      <div class="mt-4">
        <CheckBox
          v-model="form.send_notification_email"
          name="send_notification_email"
          :label="__('Send notification email with password')"
        />
      </div>

      <SubmitButton :disabled="form.disabled" />
    </form>
  </Modal>
</template>
  
  <script>
import {
  Input,
  TextArea,
  SubmitButton,
  Phone,
  Select,
  CheckBox,
} from "../../../components/Form/Index.js";
export default {
  props: ["data"],
  components: {
    Input,
    TextArea,
    SubmitButton,
    Phone,
    Select,
    CheckBox,
  },
  data() {
    return {
      form: this.$inertia.form({
        send_notification_email: null,
        password: null,
        first_name: null,
        last_name: null,
        email: null,
        phone: null,
        bio: null,
        address: null,
        state: null,
        city: null,
        country_code: null,
        timezone: null,
        payment_method: null,
        payment_method_details: null,
        education_level_id: null,
        years_of_experience: null,
        author_level_id: null,

        service_id_1: null,
        service_id_2: null,
        service_id_3: null,

        subject_id_1: null,
        subject_id_2: null,
        subject_id_3: null,
        subject_id_4: null,
        subject_id_5: null,
        blog_url: null,
        online_portfolio_url: null,
        linked_in_url: null,
      }),      
    };
  },
};
</script>