<template>
  <Modal :title="data.title">
    <form
      @submit.prevent="
        form.post(
          route('admin.invoices.adjust.from.wallet.store', invoice.uuid),
          formConfig
        )
      "
    >
      <Input
        v-model="form.amount"
        name="amount"
        :label="__('Amount')"
        :required="true"
        @keypress="onlyNumber($event, form.amount)"
        :tooltip="__('The amount that will be adjusted from wallet')"
      />
      <SubmitButton :disabled="form.processing" />
    </form>
  </Modal>
</template>
  
  <script>
import { Input, SubmitButton } from "../../../components/Form/Index.js";

export default {
  components: {
    Input,
    SubmitButton,
  },
  props: ["data", "invoice"],
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
      return {
        amount: this.data.balance_due,
      };
    },
  },
};
</script>