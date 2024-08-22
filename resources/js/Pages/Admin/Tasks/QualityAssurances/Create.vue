<template>
  <Modal :title="data.title" size="regular">
    <form @submit.prevent="form.post(data.config.urls.submit_form)">
      <div class="mb-3">
        <label class="form-label"
          >{{ __("Enter your findings") }}
          <span class="required">*</span></label
        >
        <QuillEditor
          style="height: 150px"
          v-model:content="form.message"
          contentType="html"
          theme="snow"
          toolbar="essential"
        />
        <ValidationError name="message" />
      </div>
      <div class="mb-3">
        <Attachment
          @onChange="handleAttachment"
          :upload_attachment_url="data.config.urls.upload_attachment"
          :allowed_file_extensions="data.config.allowed_file_extensions"
          :maximum_number_of_files_to_upload="
            data.config.maximum_number_of_files_to_upload
          "
          :maximum_file_size="data.config.maximum_file_size"
          :existing_files="data.config.existing_files"
        ></Attachment>
        <ValidationError name="files" />
      </div>

      <div class="mt-3">
        <div class="form-check form-check-inline">
          <input
            class="form-check-input"
            type="radio"
            id="approve"
            value="approve"
            v-model="form.feedback"
          />
          <label class="form-check-label" for="approve">{{
            __("Approve")
          }}</label>
        </div>
        <div class="form-check form-check-inline">
          <input
            class="form-check-input"
            type="radio"
            id="reject"
            value="reject"
            v-model="form.feedback"
          />
          <label class="form-check-label" for="reject">{{
            __("Reject")
          }}</label>
        </div>
        <ValidationError name="feedback" />
      </div>

      <div class="mt-3">
        <button
          :disabled="form.processing"
          class="btn btn-primary"
          type="submit"
        >
          <i class="far fa-paper-plane"></i> {{ __("Submit") }}
        </button>
      </div>
    </form>
  </Modal>
</template>
<script>
import Attachment from "../../../../components/Attachment.vue";
import { QuillEditor } from "@vueup/vue-quill";
import "@vueup/vue-quill/dist/vue-quill.snow.css";

export default {
  components: {
    Attachment,
    QuillEditor,
  },
  props: ["data"],
  data() {
    return {
      form: this.$inertia.form({
        feedback: "",
        message: this.data.config.default_message,
        files: this.data.config.existing_files
          ? this.data.config.existing_files
          : [],
      }),
    };
  },
  methods: {
    handleAttachment(files) {
      this.form.files = files;
    },
  },
};
</script>
