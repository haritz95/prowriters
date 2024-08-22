<template>
  <Attachment
    @onChange="handleAttachment"
    :upload_attachment_url="data.config.urls.upload_attachment"
    :allowed_file_extensions="data.config.allowed_file_extensions"
    :maximum_number_of_files_to_upload="
      data.config.maximum_number_of_files_to_upload
    "
    :maximum_file_size="data.config.maximum_file_size"
    :existing_files="data.config.existing_files"
    :clear_existing_files="clear_existing_files"
  ></Attachment>
  <ValidationError name="files" />
  <div class="mt-3" v-if="this.form.attachments.length > 0">
    <button
      @click="submitForm"
      :disabled="form.processing"
      class="btn btn-sm btn-primary"
      type="submit"
    >
      <i class="far fa-paper-plane"></i> {{ __("Submit") }}
    </button>
  </div>
</template>

<script>
import Attachment from "./Attachment.vue";

export default {
  props: ["data"],
  components: {
    Attachment,
  },
  data() {
    return {
      clear_existing_files: false,
      form: this.$inertia.form(
        {
          attachments: [],
        },
        {
          preserveState: false,
        }
      ),
    };
  },
  methods: {
    handleAttachment(files) {
      this.form.attachments = files;
    },
    submitForm() {
      let scope = this;
      this.form.post(this.data.config.urls.submit_form, {
        onSuccess: function () {
          scope.form.reset();
          scope.clear_existing_files = true;
        },
      });
    },
  },
};
</script>