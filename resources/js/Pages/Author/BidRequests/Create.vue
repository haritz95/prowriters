<template>
  <Modal :title="data.title">
    <form
      @submit.prevent="
        existing_record
          ? form.patch(route('author.bids.update', existing_record.uuid))
          : form.post(route('author.bidRequests.bids.store', data.bidRequest_uuid))
      "
    >
      <Input
        v-model="form.total"
        name="total"
        :label="__('Bidding Amount')"
        :required="true"
        @keypress="onlyNumber($event, form.total)"
        :tooltip="__('The final amount that will be charged to the customer')"
      />
      <div class="mb-2">
        <small class="text-success">{{ __("You will receive") }} {{ formatMoney(author_payment_amount) }}</small>
      </div>

      <Input
        v-model="form.duration_days"
        name="duration_days"
        :label="__('Number of days to complete')"
        :required="true"
        @keypress="onlyNumber($event, form.duration_days)"
      />

      <Input
        v-model="form.number_of_revisions"
        name="number_of_revisions"
        :label="__('Number of revisions')"
        :required="true"
        @keypress="onlyNumber($event, form.number_of_revisions)"
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
  props: ["data", "existing_record"],
  watch: {
    "form.total": {
      handler(newValue, oldValue) {
        this.calculateAuthorPayment(newValue);
      },
      deep: true,
    },
  },
  data() {
    return {
      form: this.$inertia.form(this.prepareForm()),
      formConfig: {
        preserveScroll: false,
      },
      author_payment_amount: 0,
    };
  },
  methods: {
    prepareForm() {
      let inputs = {
        total: null,
        duration_days: null,
        number_of_revisions: null,
      };
      if (this.existing_record) {
        inputs = { ...inputs, ...this.existing_record };
      }
      return inputs;
    },
    calculateAuthorPayment(total) {
      total = parseFloat(total);
      let rate = parseFloat(this.data.platform_commission_rate);
      let company_commission = (total * rate) / 100;
      this.author_payment_amount = total - company_commission;
    },
  },
};
</script>
