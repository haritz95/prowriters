<template>
  <Modal :title="data.title">
    <form
      @submit.prevent="
        existing_record
          ? form.patch(route('admin.urgencies.update', existing_record.id))
          : form.post(route('admin.urgencies.store'))
      "
    >
      <Input
        v-model="form.name"
        name="name"
        :label="__('Display Name')"
        :required="true"
      />

      <div class="mb-3">
        <label class="form-label"
          >{{ __("Duration and Type") }} <span class="required">*</span></label
        >
        <div class="input-group">
          <input
            v-model="form.value"
            type="text"
            class="form-control form-control-sm w-75"
            @keypress="onlyNumber($event, form.value)"
            :placeholder="__(3)"
          />
          <select class="form-select form-select-sm w-25" v-model="form.type">
            <option
              v-for="(option, index) in data.dropdowns.urgency_types"
              v-bind:value="option.id"
              :key="index"
            >
              {{ option.name }}
            </option>
          </select>
        </div>
        <ValidationError name="value" />
      </div>

      <div class="mb-3">
        <label class="form-label"
          >{{ __("Duration and Type for Authors") }}
          <span class="required">*</span></label
        >
        <div class="input-group">
          <input
            v-model="form.value_for_author"
            type="text"
            class="form-control form-control-sm w-75"
            @keypress="onlyNumber($event, form.value_for_author)"
            :placeholder="__(2)"
          />
          <select class="form-select form-select-sm w-25" v-model="form.type_for_author">
            <option
              v-for="(option, index) in data.dropdowns.urgency_types"
              v-bind:value="option.id"
              :key="index"
            >
              {{ option.name }}
            </option>
          </select>
        </div>
        <ValidationError name="value_for_author" />
      </div>

      <Input
        v-model="form.percentage"
        :label="__('Markup percentage to add to the price of the service')"
        :tooltip="
          __(
            'The markup will be used in calculating the base price of the service'
          )
        "
        name="percentage"
        @keypress="onlyNumber($event, form.percentage)"
      />

      <SubmitButton :disabled="form.processing" />
    </form>
  </Modal>
</template>

<script>
import { Input, SubmitButton } from "../../../../components/Form/Index.js";

export default {
  components: {
    Input,
    SubmitButton,
  },
  props: ["data", "existing_record"],
  data() {
    return {
      form: this.$inertia.form(this.prepareForm()),  
    };
  },
  methods: {
    prepareForm() {
      let inputs = {
        percentage: null,
        name: null,
        value: null,
        type: !this.existing_record
          ? this.data.dropdowns.urgency_types[0].id
          : null,
        type_for_author: !this.existing_record
          ? this.data.dropdowns.urgency_types[0].id
          : null,
        value_for_author: null,
      };
      if (this.existing_record) {
        inputs = { ...inputs, ...this.existing_record };
      }
      return inputs;
    },
  },
};
</script>