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
            :options="data.dropdowns.academic_levels"
            v-model="form.academic_level_id"
            name="academic_level_id"
            :label="__('Academic Level')"
          />       
         
          <RadioBox
            :label="__('Paper Format')"
            v-model="form.paper_format_id"
            :options="data.dropdowns.paper_formats"
            name="paper_format_id"
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

          <AdditionalServicesField
            v-model="form.added_additional_services"
            :basic_price="basic_price"
            :additional_services="data.dropdowns.service.additional_services"
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
        let academic_level = this.data.dropdowns.service_academic_levels[
          this.form.academic_level_id
        ];

        let author_level = this.data.dropdowns.service_author_levels[
          this.form.author_level_id
        ];

        let price =
          parseFloat(unit.price) +
          this.calculatePercentage(unit.price, academic_level.percentage) +
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
      // if (this.data.existing_record) {
      //   inputs = { ...inputs, ...this.data.existing_record };
      // }
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
