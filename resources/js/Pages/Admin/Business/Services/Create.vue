<template>
  <Modal :title="data.title" size="large">
    <form>
      <div class="row">
        <div class="col-md-6">
          <Select
            :searchable="true"
            :options="data.dropdowns.service_types"
            v-model="form.service_type_id"
            :label="__('Service Order Form Type')"
            :required="true"
            name="service_type_id"
            v-if="!(existing_record && existing_record.slug)"
          />

          <Input v-model="form.name" name="name" :label="__('Name')" :required="true" />

          <TextArea
            v-model="form.description"
            name="description"
            :label="__('Description')"
            :required="true"
          />

          <Input
            v-model="form.assignment_label"
            name="assignment_label"
            :label="__('Assignment Label')"
            :required="true"
            :placeholder="__('example: Type of writing')"
          />

          <Input
            v-model="form.commission"
            name="commission"
            :label="__('Your earning commission rate from an order')"            
            @keypress="onlyNumber($event, form.commission)"
            :tooltip="__('Commission you would like to receive from the order total')"
            :note="__('In Percentage')"
          />
          <Input
            v-model="form.commission_from_bid"
            name="commission_from_bid"
            :label="__('Your earning commission rate from a bid')"            
            @keypress="onlyNumber($event, form.commission_from_bid)"
            :tooltip="__('Commission you would like to receive from bidding service')"
          />
        </div>

        <div class="col-md-6">
          <FileChooser
            v-model="form.image"
            name="image"
            :label="__('Cover image')"
            :required="true"
          />

          <div class="row">
            <div class="col-md-6">
              <Input
                v-model="form.unit_name"
                name="unit_name"
                :label="__('Unit Name')"
                :required="true"
                :placeholder="__('example: words')"
              />
            </div>
            <div class="col-md-6">
              <Input
                v-model="form.minimum_order_quantity"
                name="minimum_order_quantity"
                :label="__('Minimum order quantity')"
                :required="true"
                @keypress="onlyNumber($event, form.minimum_order_quantity)"
              />
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <Input
                v-model="form.maximum_file_size"
                name="maximum_file_size"
                :label="__('Maximum file size in KB')"
                :required="true"
                @keypress="onlyNumber($event, form.maximum_file_size)"
              />
            </div>
            <div class="col-md-6">
              <Input
                v-model="form.maximum_number_of_files_to_upload"
                name="maximum_number_of_files_to_upload"
                :label="__('Maximum number of files')"
                :required="true"
                @keypress="onlyNumber($event, form.maximum_number_of_files_to_upload)"
              />
            </div>
          </div>

          <Input
            v-model="form.allowed_file_extensions"
            name="allowed_file_extensions"
            :label="__('Allowed file extensions')"
            :required="true"
            :placeholder="__('example: .jpg,.png,.gif, .doc,.docx,.xls')"
          />

          <div class="mt-3">
            <CheckBox v-model="form.inactive" name="inactive" :label="__('Inactive')" />
            <CheckBox
              v-model="form.not_available_for_bidding"
              name="not_available_for_bidding"
              :label="__('Not available for Bidding')"
            />
            <CheckBox
              v-model="form.not_available_for_direct_order"
              name="not_available_for_direct_order"
              :label="__('Not available for Direct Order')"
            />
          </div>
        </div>
      </div>
    </form>

    <template v-slot:footer>
      <button
        @click="
          existing_record
            ? form.patch(route('admin.services.update', existing_record.slug))
            : form.post(route('admin.services.store'))
        "
        :disabled="form.processing"
        type="submit"
        class="btn btn-primary"
      >
        {{ __("Submit") }}
      </button>
    </template>
  </Modal>
</template>

<script>
import {
  Input,
  TextArea,
  FileChooser,
  CheckBox,
  Select,
  SubmitButton,
} from "../../../../components/Form/Index.js";

export default {
  components: {
    Input,
    TextArea,
    FileChooser,
    CheckBox,
    SubmitButton,
    Select,
  },
  props: ["data", "existing_record"],
  data() {
    return {
      form: this.$inertia.form(this.prepareForm()),
      formConfig: {
        preserveScroll: false,
      },
    };
  },
  methods: {
    prepareForm() {
      let inputs = {
        service_type_id: null,
        name: null,
        description: null,
        assignment_label: null,
        image: null,
        unit_name: null,
        minimum_order_quantity: null,
        maximum_file_size: null,
        maximum_number_of_files_to_upload: null,
        allowed_file_extensions: null,
        inactive: null,
        not_available_for_direct_order: null,
        not_available_for_bidding: null,
        commission: null,
        commission_from_bid: null,
      };
      if (this.existing_record) {
        inputs = { ...inputs, ...this.existing_record };
      }
      return inputs;
    },
  },
};
</script>
