<template>
  <Modal :title="data.title" size="full_screen">
    <Input v-model="form.name" name="name" :label="__('Name')" :required="true" />

    <Input
      v-model="form.price"
      name="price"
      :label="__('Price')"
      :required="true"
      @keypress="onlyNumber($event, form.price)"
    />
  </Modal>
</template>

<script>
import DestroyButton from "../../../../components/Form/DestroyButton.vue";

import { Input } from "../../../../components/Form/Index.js";

export default {
  components: {
    Input,
    DestroyButton,
  },
  props: ["data"],
  data() {
    return {
      form: this.$inertia.form(this.prepareForm()),
      formConfig: {
        onSuccess: () => this.form.reset(),
      },
    };
  },
  methods: {
    prepareForm() {
      let inputs = {
        name: null,
        urgency_id: null,
        price: 0,
        url_query_parameters: this.data.url_query_parameters,
      };
      if (this.existing_record) {
        inputs = { ...inputs, ...this.existing_record };
      }
      return inputs;
    },
  },
};
</script>
