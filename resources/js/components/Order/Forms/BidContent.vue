<template>
  <div class="row">
    <div class="col-md-8">
      <div class="border shadow-sm">
        <h5 class="order-form-section-title">
          {{ __("General Information") }}
        </h5>

        <div class="ps-4 pe-4">
          <Select
            :searchable="true"
            :options="data.dropdowns.service.assignments"
            v-model="form.assignment_id"
            name="assignment_id"
            :label="__(data.dropdowns.service.assignment_label)"
          />

          <div class="row">
            <div class="col-md-6">
              <InputSpinner
                v-model="form.number_of_words"
                :step="1"
                :min="275"
                :label="__('Number of words')"
              />
            </div>
          </div>

          <Select
            :searchable="true"
            :options="data.dropdowns.service.subjects"
            v-model="form.subject_id"
            name="subject_id"
            :label="__('Subject')"
          />

          <Select
            :searchable="true"
            :options="data.dropdowns.languages"
            v-model="form.language_id"
            name="language_id"
            :label="__('Language')"
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
        <div class="p-2">
          <Input
            v-model="form.title"
            name="title"
            :label="__('Title')"
            :required="true"
            :placeholder="__('Enter the title')"
          />

          <TextArea
            v-model="form.content_goals"
            name="content_goals"
            :label="__('Content Goals & Things to Mention')"
            :required="true"
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

          <Select
            v-model="form.grammatical_person_id"
            :options="data.dropdowns.grammatical_people"
            :label="__('Grammatical Person')"
            name="grammatical_person_id"
          />
          <TextArea
            v-model="form.target_audience"
            :label="__('Target Audience')"
            name="target_audience"
          />
          <TextArea
            v-model="form.target_keywords"
            :label="__('Target Keywords')"
            name="target_keywords"
          />
          <TextArea
            v-model="form.links_to_example_content"
            :label="__('Links to Example Content')"
            name="links_to_example_content"
          />
          <TextArea
            v-model="form.style_and_tone"
            :label="__('Style & Tone')"
            name="style_and_tone"
          />
          <TextArea
            v-model="form.structure_and_formatting_requirements"
            :label="__('Structure & Formatting Requirements')"
            name="structure_and_formatting_requirements"
          />
          <TextArea
            v-model="form.referencing_and_linking_preferences"
            :label="__('Referencing & Linking Preferences')"
            name="referencing_and_linking_preferences"
          />
          <TextArea
            v-model="form.things_to_avoid"
            :label="__('Things to Avoid')"
            name="things_to_avoid"
          />

          <TextArea
            name="additional_notes"
            v-model="form.additional_notes"
            :label="__('Additional Notes')"
            :placeholder="__('Include any additional notes here')"
          />
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
import { TextArea, Input, Select, InputSpinner } from "../../Form/Index.js";
import Attachment from "../../Attachment.vue";

export default {
  components: {
    TextArea,
    Input,
    Select,
    AuthorLevels,
    BidOrderSummary,
    Attachment,
    InputSpinner,
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
