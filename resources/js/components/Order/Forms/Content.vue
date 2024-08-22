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

          <Select
            :searchable="true"
            :options="units"
            v-model="form.unit_id"
            name="unit_id"
            :label="__('Word Count')"
          />

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

        <AdditionalServicesField
          v-model="form.added_additional_services"
          :basic_price="basic_price"
          :additional_services="data.dropdowns.service.additional_services"
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
      <OrderSummary :data="data" :total="calculateTotal" :form="form" />
    </div>
  </div>
</template>

<script>
import AuthorLevels from "../Fields/AuthorLevels.vue";

import AdditionalServicesField from "../Fields/AdditionalServicesField.vue";

import OrderSummary from "../OrderSummary.vue";

import { TextArea, Input, Select, RadioBox } from "../../Form/Index.js";
import Attachment from "../../Attachment.vue";

export default {
  components: {
    TextArea,
    Input,
    Select,
    RadioBox,
    AuthorLevels,
    OrderSummary,
    AdditionalServicesField,
    Attachment,
  },
  props: ["data"],
  watch: {
    "form.assignment_id": {
      handler(new_assignment_id, oldValue) {
        if (new_assignment_id) {
          this.units = this.data.dropdowns.units[new_assignment_id];
          this.form.unit_id = this.units[0].id;
        } else {
          this.units = [];
          this.form.unit_id = null;
        }
      },
      deep: true,
    },
  },
  computed: {
    calculateTotal() {
      if (this.form.unit_id) {
        let unit = this.data.dropdowns.assignment_units[this.form.unit_id];

        let subject = this.data.dropdowns.subjects[this.form.subject_id];
        let language = this.data.dropdowns.content_languages[this.form.language_id];

        let author_level = this.data.dropdowns.service_author_levels[
          this.form.author_level_id
        ];

        let price =
          parseFloat(unit.price) +
          this.calculatePercentage(unit.price, language.percentage) +
          this.calculatePercentage(unit.price, subject.percentage) +
          this.calculatePercentage(unit.price, author_level.percentage);

        this.basic_price = price;

        let sum = 0;
        if (
          this.form.added_additional_services &&
          this.form.added_additional_services.length > 0
        ) {
          this.form.added_additional_services.forEach((row) => {
            sum += parseFloat(row.total);
          });
        }

        return price + sum;
      }
    },
  },
  data() {
    return {
      basic_price: 0,
      units: this.data.dropdowns.units[this.data.existing_record.assignment_id],
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
      if (
        !inputs.added_additional_services ||
        inputs.added_additional_services.length == 0
      ) {
        inputs.added_additional_services = [];
      }
      return inputs;
    },
    calculatePercentage(price, rate) {
      price = parseFloat(price);
      rate = parseFloat(rate);
      if (rate > 0) {
        return parseFloat((price * rate) / 100);
      }
      return 0;
    },
  },
};
</script>
