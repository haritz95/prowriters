<template>
  <AppHead :title="data.title" />
  <AccountLayout :author="author">
    <div class="card">
      <div class="card-header h5">
        {{ data.title }}
      </div>
      <div class="card-body">
        <form @submit.prevent="form.patch(data.urls.submit_form, formConfig)">
          <div class="w-100 p-2 bg-light mb-2 h5">{{ __("Education & Experience") }}</div>
          <div class="p-2">
            <Select
              :searchable="true"
              :clearable="true"
              :options="data.dropdowns.education_levels"
              v-model="form.education_level_id"
              :label="__('Highest Level of Education')"
              :required="true"
              name="education_level_id"
            />

            <Input
              type="number"
              v-model="form.years_of_experience"
              :label="__('Years of Experience')"
              name="years_of_experience"
            />
          </div>
          <div class="w-100 p-2 bg-light mb-2 h5">
            {{ __("Area of Expertise") }}
          </div>

          <div class="p-2">
            <Select
              :searchable="true"
              :clearable="true"
              :options="data.dropdowns.subjects"
              v-model="form.subject_id_1"
              :label="__('Area 1')"
              :required="true"
              name="subject_id_1"
            />

            <Select
              :searchable="true"
              :clearable="true"
              :options="data.dropdowns.subjects"
              v-model="form.subject_id_2"
              :label="__('Area 2')"
              :required="true"
              name="subject_id_2"
            />
            <Select
              :searchable="true"
              :clearable="true"
              :options="data.dropdowns.subjects"
              v-model="form.subject_id_3"
              :label="__('Area 3')"
              :required="true"
              name="subject_id_3"
            />
          </div>

          <div class="w-100 p-2 bg-light mb-2 h5">{{ __("Portfolio") }}</div>
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
  Select,
} from "../../../components/Form/Index.js";

export default {
  props: ["data", "author"],
  components: {
    AccountLayout,
    Input,
    TextArea,
    SubmitButton,
    Select,
  },
  data() {
    return {
      form: this.$inertia.form({
        education_level_id: this.author.profile.education_level_id,
        years_of_experience: this.author.profile.years_of_experience,

        subject_id_1: this.author.profile.subject_id_1,
        subject_id_2: this.author.profile.subject_id_2,
        subject_id_3: this.author.profile.subject_id_3,
        blog_url: this.author.profile.blog_url,
        online_portfolio_url: this.author.profile.online_portfolio_url,
        linked_in_url: this.author.profile.linked_in_url,
      }),
      formConfig: {
        preserveScroll: false,
      },
    };
  },
};
</script>