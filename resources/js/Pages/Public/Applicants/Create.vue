<template>
  <AppHead :title="data.page.additional_data.meta_title">
    <meta name="description" :content="data.page.additional_data.meta_description" />
    <meta name="keywords" :content="data.page.additional_data.meta_keywords" />
    <meta property="og:title" :content="data.page.additional_data.meta_title" />
    <meta property="og:image" :content="asset(data.page.additional_data.meta_image)" />
  </AppHead>

  <div class="container page-container">
    <div class="row">
      <div class="col-md-12">
        <h2 class="card-title">{{ data.page.title }}</h2>
        <hr />
        <div class="alert alert-success" role="alert" v-if="data.success_message">
          {{ data.success_message }}
        </div>
      </div>

      <div class="col-md-6">
        <fieldset class="border rounded-3 p-3">
          <legend class="float-none w-auto px-3 author-application-form-title">
            {{ data.page.additional_data.form_title }}
          </legend>

          <small class="text-muted">{{ data.page.additional_data.form_sub_title }}</small>
          <div class="mb-4"></div>
          <form
            @submit.prevent="
              form.post(route('public.author.application.store'), formConfig)
            "
          >
            <div class="row">
              <div class="col-md-6">
                <Input
                  v-model="form.first_name"
                  :label="__('First Name')"
                  name="first_name"
                  :required="true"
                />
              </div>
              <div class="col-md-6">
                <Input
                  v-model="form.last_name"
                  :label="__('Last Name')"
                  name="last_name"
                  :required="true"
                />
              </div>
            </div>

            <TextArea v-model="form.bio" :label="__('Career summary')" name="bio" />

            <div class="w-100 p-2 bg-light mb-2 h5">
              {{ __("Experience & Skill") }}
            </div>
            <div class="p-2">
              <Select
                :searchable="true"
                :clearable="true"
                :options="data.dropdowns.education_levels"
                v-model="form.education_level_id"
                :label="__('Highest Education Level')"
                :required="true"
                name="education_level_id"
              />

              <Select
                :searchable="true"
                :clearable="false"
                :options="data.dropdowns.experiences"
                v-model="form.years_of_experience"
                :label="__('Years of Experience')"
                :required="true"
                name="years_of_experience"
              />

              <Select
                :searchable="true"
                :clearable="false"
                :options="data.dropdowns.services"
                v-model="form.service_id_1"
                :label="__('Type of service you are interested in')"
                :required="true"
                name="service_id_1"
                @change="serviceChanged"
              />

              <Select
                v-if="displaySubjects"
                :searchable="true"
                :clearable="false"
                :options="data.dropdowns.subjects[form.service_id_1]"
                v-model="form.subject_id_1"
                :label="__('Primary Subject')"
                name="subject_id_1"
                :note="text_optional"
              />

              <Select
                v-if="displaySubjects"
                :searchable="true"
                :clearable="true"
                :options="data.dropdowns.subjects[form.service_id_1]"
                v-model="form.subject_id_2"
                :label="__('Subject 2')"
                name="subject_id_2"
                :note="text_optional"
              />
              <Select
                v-if="displaySubjects"
                :searchable="true"
                :clearable="true"
                :options="data.dropdowns.subjects[form.service_id_1]"
                v-model="form.subject_id_3"
                :label="__('Subject 3')"
                name="subject_id_3"
                :note="text_optional"
              />
              <Select
                v-if="displaySubjects"
                :searchable="true"
                :clearable="true"
                :options="data.dropdowns.subjects[form.service_id_1]"
                v-model="form.subject_id_4"
                :label="__('Subject 4')"
                name="subject_id_4"
                :note="text_optional"
              />
              <Select
                v-if="displaySubjects"
                :searchable="true"
                :clearable="true"
                :options="data.dropdowns.subjects[form.service_id_1]"
                v-model="form.subject_id_5"
                :label="__('Subject 5')"
                name="subject_id_5"
                :note="text_optional"
              />

              <Select
                :searchable="true"
                :clearable="true"
                :options="data.dropdowns.languages"
                v-model="form.language_id_1"
                :label="__('Primary Language')"
                :required="true"
                name="language_id_1"
              />

              <Select
                :searchable="true"
                :clearable="true"
                :options="data.dropdowns.languages"
                v-model="form.language_id_2"
                :label="__('Secondary Language')"
                name="language_id_2"
              />
            </div>

            <div class="w-100 p-2 bg-light mb-2 h5">
              {{ __("Contact Information") }}
            </div>
            <div class="p-2">
              <Input
                type="email"
                v-model="form.email"
                :label="__('Email')"
                name="email"
                :required="true"
              />

              <Phone
                v-model="form.phone"
                :label="__('Phone')"
                name="phone"
                :required="true"
              />

              <Input v-model="form.address" :label="__('Address')" name="address" />
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
                name="country_code"
              />

              <Select
                :searchable="true"
                :options="data.dropdowns.timezones"
                v-model="form.timezone"
                :label="__('Timezone')"
                :required="true"
                name="timezone"
              />
            </div>

            <div class="w-100 p-2 bg-light mb-2 h5">{{ __("Links") }}</div>
            <div class="p-2">
              <Input v-model="form.blog_url" :label="__('Blog URL')" name="blog_url" />
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

            <label class="form-label"
              >{{ __("Upload Your Resume") }} <span class="required">*</span></label
            >
            <Attachment
              @onChange="handleAttachment"
              :upload_attachment_url="data.config.urls.upload_attachment"
              :allowed_file_extensions="data.config.allowed_file_extensions"
              :maximum_number_of_files_to_upload="
                data.config.maximum_number_of_files_to_upload
              "
              :maximum_file_size="data.config.maximum_file_size"
              :existing_files="data.config.existing_files"
              :config="data.config"
            ></Attachment>
            <ValidationError name="attachments" />

            <SubmitButton :disabled="form.disabled" />
          </form>
        </fieldset>
      </div>

      <div class="col-md-6">
        <div class="" v-html="data.page.content"></div>
      </div>
    </div>
  </div>
</template>

<script>
//   import { useGoogleCaptcha } from "../../composable/Recaptcha.js";

import {
  Input,
  TextArea,
  SubmitButton,
  Phone,
  Select,
  CheckBox,
} from "../../../components/Form/Index.js";

import Attachment from "../../../components/Attachment.vue";

export default {
  props: ["data"],
  components: {
    Input,
    TextArea,
    SubmitButton,
    Phone,
    Select,
    CheckBox,
    Attachment,
  },
  computed: {
    displaySubjects() {
      return this.form.service_id_1;
    },
  },
  data() {
    return {
      text_optional: "(" + this.__("Optional") + ")",
      form: this.$inertia.form({
        first_name: null,
        last_name: null,
        bio: null,

        email: null,
        phone: null,
        address: null,
        state: null,
        city: null,
        country_code: null,
        timezone: null,

        education_level_id: null,
        years_of_experience: null,
        service_id_1: null,

        subject_id_1: null,
        subject_id_2: null,
        subject_id_3: null,
        subject_id_4: null,
        subject_id_5: null,
        language_id_1: null,
        language_id_2: null,

        blog_url: null,
        online_portfolio_url: null,
        linked_in_url: null,
        attachment: null,
      }),
      formConfig: {
        preserveScroll: false,
        onSuccess: () => this.form.reset(),
      },
    };
  },
  methods: {
    handleAttachment(files) {
      this.form.attachment = files;
    },
    // resetForm(){
    //   Object.keys(form.data()).forEach(field => form.defaults(field, null));
    // },
    serviceChanged() {
      this.form.subject_id_1 = null;
      this.form.subject_id_2 = null;
      this.form.subject_id_3 = null;
      this.form.subject_id_4 = null;
      this.form.subject_id_5 = null;
    },
  },
};
</script>
