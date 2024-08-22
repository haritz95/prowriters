<template>
  <Modal :title="data.title">
    <form
      @submit.prevent="
        existing_record
          ? form.patch(
              route('admin.additionalServices.update', existing_record.slug)
            )
          : form.post(route('admin.additionalServices.store'))
      "
    >
      <div class="mb-3" v-if="!existing_record">
        <label class="form-label">{{ __("Price Type") }}</label>
        <v-select
          :reduce="(option) => option.id"
          v-model="form.type"
          :options="data.dropdowns.price_types"
          label="name"
          :clearable="false"
          :searchable="false"
          :placeholder="__('Select')"
        >
          <template v-slot:option="option">
            <div>{{ option.name }}</div>
            <small class="text-muted">{{ option.description }}</small>
          </template>
        </v-select>
        <ValidationError name="type" />
      </div>

      <Input
        v-model="form.name"
        name="name"
        :label="__('Name')"
        :required="true"
      />

      <Input
        v-if="form.type == data.per_entered_quantity"
        v-model="form.per_entered_quantity_label"
        name="per_entered_quantity_label"
        :label="__('Quantity Label')"
        :required="true"
      />

      <TextArea
        rows="2"
        v-model="form.description"
        name="description"
        :label="__('Description')"
        :required="true"
      />

      <Input
        v-model="form.price"
        name="price"
        :label="__('Price')"
        :required="true"
        @keypress="onlyNumber($event, form.price)"
      />

      <Select
        :multiple="true"
        :searchable="true"
        :options="data.dropdowns.services"
        v-model="form.services"
        :label="__('Services')"
        :required="true"
        name="services"
      />

      <SubmitButton :disabled="form.processing" />
    </form>
  </Modal>
</template>

<script>
import {
  Input,
  TextArea,
  Select,
  SubmitButton,
} from "../../../../components/Form/Index.js";

export default {
  components: {
    Input,
    Select,
    TextArea,
    SubmitButton,
  },
  props: ["data", "existing_record"],
  data() {
    return {
      form: this.$inertia.form(this.prepareForm()),
      formConfig: {
        preserveScroll: false,
        onSuccess: () => this.form.reset(),
      },
    };
  },
  methods: {
    prepareForm() {
      let inputs = {
        name: null,
        type: null,
        description: null,
        per_entered_quantity_label: null,
        price: 0,
        services : [],
      };
      if (this.existing_record) {
        inputs = { ...inputs, ...this.existing_record };
      }
      return inputs;
    },
  },
};
</script>