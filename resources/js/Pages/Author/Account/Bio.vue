<template>
  <AppHead :title="data.title" />
  <AccountLayout :author="author">
    <div class="card">
      <div class="card-header h5">
        {{ data.title }}
      </div>
      <div class="card-body">
        <form @submit.prevent="form.patch(data.urls.submit_form, formConfig)">
          <div class="mb-3">
            <label class="form-label"
              >{{ __("Tell us about yourself") }} <span class="required">*</span></label
            >            
            <Editor v-model="form.bio" :height="'300px'" />
            <ValidationError name="bio" />
          </div>
          <SubmitButton :disabled="form.disabled" />
          
        </form>
      </div>
    </div>
  </AccountLayout>
</template>

<script>
import AccountLayout from "./Partials/AccountLayout.vue";
import Editor from "../../../components/Editor.vue";

import { SubmitButton } from "../../../components/Form/Index.js";

export default {
  props: ["data", "author"],
  components: {
    AccountLayout,
    Editor,
    SubmitButton,
  },
  data() {
    return {
      form: this.$inertia.form({
        bio: this.author.profile.bio,
      }),
      formConfig: {
        preserveScroll: false,
      },
    };
  },
};
</script>