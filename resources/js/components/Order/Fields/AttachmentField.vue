<template>
  <div :class="$store.getters.constants.style.formGroup">
    <label :class="$store.getters.constants.style.formLabel">{{
      __("Attachment")
    }}</label>

    <Attachment
      @onChange="handleAttachment"
      :allowed_file_extensions="
        $store.state.serviceModel.allowed_file_extensions
      "
      :maximum_file_size="$store.state.serviceModel.maximum_file_size"
      :maximum_number_of_files_to_upload="
        $store.state.serviceModel.maximum_number_of_files_to_upload
      "
      :upload_attachment_url="$store.state.urls.upload_attachment"
      :existing_files="$store.state.initial_records.files_data"
    ></Attachment>
    <ErrorField name="files_data" />  
  </div>
</template>
<script>
import Attachment from "../../Attachment.vue";

export default {
  inject: ["$store"],
  components: {
    Attachment,
  },
  methods: {
    handleAttachment(files) {
      this.$store.state.form.files_data = files;
    },
  },
};
</script>
