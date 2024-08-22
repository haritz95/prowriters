<template>
  <form
    @submit.prevent="
      existing_record
        ? form.patch(data.urls.update_content)
        : form.post(data.urls.store_content)
    "
  >
  <div class="input-group">
    <Link :href="data.urls.index_content" class="btn btn-light input-group-text border"><i class="fa-solid fa-arrow-left"></i></Link>
    
      <input
        v-model="form.title"
        class="form-control border" :placeholder="__('Enter your title')"
      />
    </div>
    <Editor v-model="form.content" height="400px" />

    <SubmitButton class="mt-2" :button_text="__('Save Content')" />
  </form>
</template>

<script>
import { Input, SubmitButton } from "../../components/Form/Index.js";
import Editor from "../../components/Editor.vue";

export default {
  props: ["data", "existing_record"],
  components: {
    Input,
    SubmitButton,
    Editor,
  },
  watch: {
    existing_record(newValue) {
      this.setExistingRecord(newValue);
    },
  },
  created() {
    if (this.existing_record) {
      this.setExistingRecord(this.existing_record);
    }
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
        title: null,
        content: "",
      };
    },
    setExistingRecord(existing_record) {
      if (existing_record) {
        this.form.title = existing_record.title;
        this.form.content = existing_record.content;
      }
    },
  },
};
</script>

<style>
[contentEditable="true"]:empty:not(:focus)::before {
  content: attr(placeholder);
  color: grey;
}
</style>
