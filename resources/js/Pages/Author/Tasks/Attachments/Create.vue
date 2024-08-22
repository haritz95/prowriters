<template>
  <Modal :title="__('Upload attachment')" size="regular">
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
  </Modal>
</template>

<script>
import Attachment from "../../../../components/Attachment.vue";
import Modal from "../../../../components/Modal.vue";
export default {
  props: ["task", "data"],
  components: {
    Attachment,
    Modal,
  },
  data() {
    return {
      form: this.$inertia.form(
        {
          attachments: [],
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
      this.form.attachments = files;
    },
    submitForm() {
      this.form.post(this.data.config.urls.submit_form);
    },
  },
};
</script>