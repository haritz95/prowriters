<template>
  <CheckoutLayout
    :data="data"
    :title="data.title"
    :is_payment_gateway_page="true"
  >
    <div v-if="gateway.instruction" class="mb-4">
      <div class="fw-bolder">{{ __("Instructions") }}</div>
      <div class="pre-formatted text-muted border p-2">
        {{ gateway.instruction }}
      </div>
    </div>
    <form @submit.prevent="form.post(data.urls.submit_form, formConfig)">
      <Input
        v-if="gateway.settings.requires_transaction_number"
        v-model="form.reference"
        :label="__(gateway.settings.reference_field_label)"
        name="reference"
        :required="true"
      />

      <div class="mb-3" v-if="gateway.settings.requires_uploading_attachment">
        <label for="attachment" class="form-label"
          >{{ __(gateway.settings.attachment_field_label) }}
          <span class="required">*</span>
          <span
            class="ms-1"
            ref="tooltip"
            data-bs-toggle="tooltip"
            data-bs-placement="top"
            :title="tooltip.attachment"
            ><i class="fa-solid fa-circle-question"></i
          ></span>
        </label>

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

        <div>
          <small class="text-muted">
            {{ __("Maximum file size") }}: {{ data.config.maximum_file_size }}
            {{ __("MB") }}
          </small>
        </div>
      </div>

      <div class="d-grid gap-2">
        <button
          :disabled="form.processing"
          type="submit"
          class="btn btn-success"
        >
          {{ __("Submit Request") }}
        </button>
      </div>
    </form>
  </CheckoutLayout>
</template>


<script>
import CheckoutLayout from "./CheckoutLayout.vue";
import { Input } from "../../components/Form/Index.js";
import Attachment from "../../components/Attachment.vue";

export default {
  props: ["data", "gateway"],
  components: {
    CheckoutLayout,
    Input,
    Attachment,
  },
  data() {
    return {
      form: this.$inertia.form({
        reference: "",       
        files: null,
      }),
      formConfig: {
        preserveScroll: true,
        
      },
      tooltip: {
        attachment:
          this.__("Allowed file types") +
          " " +
          this.data.config.allowed_file_extensions,
      },
    };
  },
  methods: {
    handleAttachment(files) {
      this.form.files = files;
    },
  },
};
</script>