<template>
  <form @submit.prevent="form.post(config.urls.submit_form)">
    <div class="mb-3">
      <QuillEditor
        style="height: 150px"
        v-model:content="form.message"
        contentType="html"
        theme="snow"
        toolbar="essential"
      />
      <ValidationError name="message" />
    </div>
    <Attachment
      @onChange="handleAttachment"
      :upload_attachment_url="config.urls.upload_attachment"
      :allowed_file_extensions="config.allowed_file_extensions"
      :maximum_number_of_files_to_upload="
        config.maximum_number_of_files_to_upload
      "
      :maximum_file_size="config.maximum_file_size"
      :existing_files="config.existing_files"     
      :config="config"
    ></Attachment>
    <ValidationError name="files" />    
    <div class="mt-3 text-end">
      <button
        :disabled="form.processing"
        class="btn btn-sm btn-primary"
        type="submit"
      >
        <i class="far fa-paper-plane"></i> {{ __("Submit") }}
      </button>
    </div>
  </form>
</template>
<script>
import Attachment from "./Attachment.vue";
import { QuillEditor } from "@vueup/vue-quill";
import "@vueup/vue-quill/dist/vue-quill.snow.css";

export default {
  components: {
    Attachment,
    QuillEditor,
  },
  props: ["config", "errors"],
  data() {
    return {
      form: this.$inertia.form(
        {
          message: this.config.default_message,
          files: this.config.existing_files ? this.config.existing_files : [],
        },
        {
          preserveState: false,
        },
        {
          resetOnSuccess: true,
        }
      ),
    };
  },
  methods: {
    handleAttachment(files) {
      this.form.files = files;
    },
  },
};
</script>
