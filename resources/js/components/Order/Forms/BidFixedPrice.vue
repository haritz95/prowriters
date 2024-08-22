<template>
  <div class="row">
    <div class="col-md-8">
      <div class="border shadow-sm">
        <h5 class="order-form-section-title">
          {{ __("General Information") }}
        </h5>
        <div class="ps-4 pe-4 mt-2">
          <Select
            :searchable="true"
            :options="data.dropdowns.service.assignments"
            v-model="form.assignment_id"
            name="assignment_id"
            :label="__(data.dropdowns.service.assignment_label)"
          />
        </div>

        <AuthorLevels
          :label="__('Author Level')"
          v-model="form.author_level_id"
          :author_levels="data.dropdowns.author_levels"
        />

        <h5 class="order-form-section-title">
          {{ __("Project Brief") }}
        </h5>
        <div class="ps-2 pe-2">
          <Input
            v-model="form.title"
            name="title"
            :label="__('Title')"
            :required="true"
            :placeholder="__('Enter the title')"
          />

          <TextArea
            name="instruction"
            v-model="form.instruction"
            :label="__('Instruction')"
            :placeholder="__('Type your instructions here')"
          />

          <Attachment
            @onChange="handleAttachment"
            :upload_attachment_url="data.config.urls.upload_attachment"
            :allowed_file_extensions="data.config.allowed_file_extensions"
            :maximum_number_of_files_to_upload="
              data.config.maximum_number_of_files_to_upload
            "
            :maximum_file_size="data.config.maximum_file_size"
            :existing_files="data.config.existing_files"
            :config="data.config"
          ></Attachment>
          <ValidationError name="attachments" />
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <BidOrderSummary :data="data" :form="form" />
    </div>
  </div>
</template>

<script>
import AuthorLevels from "../Fields/AuthorLevels.vue";
import BidOrderSummary from "../BidOrderSummary.vue";
import { TextArea, Input, Select } from "../../Form/Index.js";
import Attachment from "../../Attachment.vue";

export default {
  components: {
    TextArea,
    Input,
    Select,
    BidOrderSummary,
    Attachment,
    AuthorLevels,
  },
  props: ["data"],
  data() {
    return {
      form: this.$inertia.form(this.prepareForm()),
      formConfig: {
        preserveScroll: false,
      },
    };
  },
  methods: {
    handleAttachment(files) {
      this.form.attachments = files;
    },
    prepareForm() {
      let inputs = this.data.existing_record;

      return inputs;
    },
  },
};
</script>
