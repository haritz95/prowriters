<template>
  <AppHead :title="data.title" />
  <div class="container-fluid">
    <div class="row vh-100">
      <div class="col-md-4 vh-100" style="background: #f9f9f9">
        <div class="p-2" style="background: #efefef">
          {{ __("Generate Content using AI") }}
        </div>

        <form @submit.prevent="existing_record
          ? form.patch(route('customer.aIGeneratedContents.update', existing_record.uuid))
          : form.post(route('customer.aIGeneratedContents.store'))">
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
              :rows="(field.rows) ? field.rows : 2"
            />
          </template>

          <div class="d-grid gap-2">
            <button class="btn btn-primary" type="submit" :disabled="form.processing">
              {{ __("Generate Content") }} <i class="fa-solid fa-arrow-right-long"></i>
            </button>
          </div>
        </form>
      </div>

      <div class="col-md-8">
        <div>
          <input v-model="generated_content.title" class="border" style="width:100%;padding: 10px" :placeholder="__('Enter your title')"/>
        </div>
        <Editor v-model="generated_content.content" height="400px" />        
      </div>
    </div>
  </div>
</template>

<script>
import { Input, TextArea, Select, SubmitButton } from "../../../components/Form/Index.js";
import Editor from "../../../components/Editor.vue";

export default {
  props: ["data", "existing_record"],
  components: {
    Input,
    TextArea,
    Select,
    SubmitButton,
    Editor,
  },
  watch: {
    // Note: only simple paths. Expressions are not supported.
    existing_record(newValue) {
      this.generated_content = newValue;
    },
  },
  created() {
    if (this.existing_record) {
      this.generated_content = this.existing_record;
    }
  },
  data() {
    return {     
      fields: [],
      form: this.$inertia.form(this.generalFields()),
      generated_content: {
        title: null,
        content: "",
      },
    };
  },
  methods: {
    generalFields() {
      return {
        language_id: 'English',
        tone_id: 'Candid',
        use_case_id: null,
      };
    },
    handleUseCaseChange() {
      let use_case_id = this.form.use_case_id;
      let formFields = this.generalFields();

      if (use_case_id) {
        formFields = this.form;

        let fields = use_case_id ? this.data.fields[use_case_id] : [];
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

<style>
    [contentEditable=true]:empty:not(:focus)::before{
        content:attr(placeholder);
        color:grey;
    }
</style>