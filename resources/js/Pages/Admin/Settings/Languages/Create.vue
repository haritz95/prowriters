<template>
  <Modal :title="data.title">
    <form
      @submit.prevent="
        existing_record
          ? form.patch(
              route('admin.settings.systemLanguages.update', existing_record.id)
            )
          : form.post(route('admin.settings.systemLanguages.store'))
      "
    >
      <Input
        v-model="form.name"
        name="name"
        :label="__('Name')"
        :required="true"
      />

      <Select
        v-model="form.layout_direction"
        :options="data.dropdowns.layout_directions"
        name="layout_direction"
        :label="__('Layout Direction')"
        :required="true"
      />

      <Input
        v-model="form.iso_code"
        name="iso_code"
        :label="__('Language ISO')"
        :required="true"
        :note="__('alpha-2 code')"
      />
      <Input
        v-model="form.country_code"
        name="country_code"
        :label="__('Country ISO')"
        :required="true"
        :note="__('alpha-2 code')"
      />

      <CheckBox
        v-model="form.is_default"
        :label="__('Default')"
        name="is_default"
      />

      <SubmitButton :disabled="form.processing" />
    </form>
  </Modal>
</template>

<script>
import {
  Input,
  Select,
  CheckBox,
  SubmitButton,
} from "../../../../components/Form/Index.js";

export default {
  components: {
    Input,
    Select,
    CheckBox,
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
        iso_code: null,
        name: null,
        country_code: null,
        layout_direction: null,
        is_default: null,
      };
      if (this.existing_record) {
        inputs = { ...inputs, ...this.existing_record };
      }
      return inputs;
    },
  },
};
</script>