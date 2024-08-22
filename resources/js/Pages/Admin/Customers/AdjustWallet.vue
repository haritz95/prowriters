<template>
  <Modal :title="data.title">
    <form @submit.prevent="form.post(data.urls.submit_form, formConfig)">
      <Select
        :options="data.dropdowns.adjustment_types"
        v-model="form.type"
        :label="__('Adjustment Type')"
        :required="true"
        name="type"
      />

      <Input
        v-model="form.amount"
        name="amount"
        :label="__('Amount')"
        :required="true"
      />

      <Input
        v-model="form.description"
        name="description"
        :label="__('Description')"
        :required="true"
      />

      <SubmitButton :disabled="form.processing" />
    </form>
  </Modal>
</template>

<script>
import { Input, Select, SubmitButton } from "../../../components/Form/Index.js";
export default {
  props: ["data", "person"],
  components: {
    Input,
    Select,
    SubmitButton,
  },
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
        type: "add",
        amount: null,
        description: null,
      };
      return inputs;
    },
  },
};
</script>