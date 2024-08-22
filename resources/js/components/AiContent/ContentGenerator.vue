<template>
  <div class="vh-100" style="background: #f9f9f9">
    <div class="p-2" style="background: #efefef">
      {{ __("Generate Content using AI") }}
    </div>
    <form @submit.prevent="form.post(data.urls.generate_content)">
      <div class="row">
        <div class="col-md-6">
          <Select
            :options="data.dropdowns.languages"
            v-model="form.language_id"
            :label="__('Language')"
            name="language_id"
          />
        </div>
        <div class="col-md-6">
          <Select
            :searchable="true"
            :options="data.dropdowns.tones"
            v-model="form.tone_id"
            :label="__('Tone')"
            name="tone_id"
          />
        </div>
      </div>

      <Select
        :searchable="true"
        :options="data.dropdowns.use_cases"
        v-model="form.use_case_id"
        :label="__('Choose use case')"
        name="use_case_id"
        @change="handleUseCaseChange"
      />

      <template v-for="(field, index) in fields" :key="index">
        <Input
          v-if="field.type == 'input'"
          v-model="form[field.name]"
          :name="field.name"
          :label="field.label"
          :placeholder="field.placeholder"
        />

        <TextArea
          v-if="field.type == 'textarea'"
          v-model="form[field.name]"
          :name="field.name"
          :label="field.label"
          :placeholder="field.placeholder"
          :rows="field.rows ? field.rows : 2"
        />
      </template>

      <div class="d-grid gap-2">
        <button class="btn btn-primary" type="submit" :disabled="form.processing">
          {{ __("Generate Content") }} <i class="fa-solid fa-arrow-right-long"></i>
        </button>
      </div>
    </form>
  </div>
</template>

<script>
import { Input, TextArea, Select, SubmitButton } from "../../components/Form/Index.js";

export default {
  props: ["data", "existing_record"],
  components: {
    Input,
    TextArea,
    Select,
    SubmitButton,
  },

  data() {
    return {
      fields: [],
      form: this.$inertia.form(this.generalFields()),
    };
  },
  methods: {
    generalFields() {
      return {
        language_id: "English",
        tone_id: "Candid",
        use_case_id: null,
      };
    },
    handleUseCaseChange() {
      let use_case_id = this.form.use_case_id;
      let formFields = this.generalFields();

      if (use_case_id) {
        formFields = this.form;

        let fields = use_case_id ? this.data.dropdowns.fields[use_case_id] : [];
        this.fields = fields;

        for (let index = 0; index < fields.length; index++) {
          formFields[fields[index].name] = null;
        }
      }

      this.form = this.$inertia.form(formFields);
    },
  },
};
</script>
