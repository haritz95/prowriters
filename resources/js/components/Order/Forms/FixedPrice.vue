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

          <div class="border pt-4 pb-4 ps-2 pe-2 bg-light fs-8 mb-4">
            <div class="mb-2">
              <strong>{{ __("Deliverables") }} : </strong>
            </div>
            <div
              class="nl2br deliverables"
              v-html="data.dropdowns.service_assignments[form.assignment_id].deliverables"
            ></div>
          </div>
        </div>

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
import AdditionalServicesField from "../Fields/AdditionalServicesField.vue";

import OrderSummary from "../OrderSummary.vue";

import { TextArea, Input, Select, InputSpinner } from "../../Form/Index.js";
import Attachment from "../../Attachment.vue";

export default {
  components: {
    TextArea,
    Input,
    Select,
    InputSpinner,
    OrderSummary,
    AdditionalServicesField,
    Attachment,
  },
  props: ["data"],
  computed: {
    calculateTotal() {
      if (this.form.assignment_id) {
        let assignment = this.data.dropdowns.service_assignments[this.form.assignment_id];

        let price = parseFloat(assignment.price);

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
      units: this.data.dropdowns.default_units_dropdown,
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
