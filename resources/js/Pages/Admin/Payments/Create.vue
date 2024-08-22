<template>
  <AppHead :title="data.title" />
  <div class="container page-container">
    <PageTitle :title="data.title">
      <Link class="btn btn-sm btn-light" :href="route('admin.payments.index')">
        <i class="fa-solid fa-arrow-left-long"></i> {{ __("Back to payments") }}
      </Link>
    </PageTitle>
    <form @submit.prevent="form.post(route('admin.payments.store'), formConfig)">
      <div class="row">
        <div class="col-md-6">
          <SearchCustomer
            :options="[]"
            v-model="form.customer_id"
            :label="__('Customer')"
            name="customer_id"
            :required="true"
          />

          <Input
            v-model="form.amount"
            :label="__('Amount')"
            @keypress="onlyNumber($event, form.amount)"
            name="amount"
            note="The amount will be added to the wallet of the selected customer"
            :required="true"
          />       

          <Input v-model="form.method" :label="__('Payment Method')" name="method" :required="true"/>

          <Input v-model="form.reference" :label="__('Reference')" name="reference" />
        </div>

        <div class="col-md-6">
          <DatePicker
            format="yyyy-MM-dd"
            v-model="form.date"
            :label="__('Payment Date')"
            name="date"
            :required="true"
          />

          <TextArea
            v-model="form.internal_note"
            rows="2"
            :label="__('Internal Note')"
            name="internal_note"
          />
        </div>
      </div>

      <SubmitButton :disabled="form.processing" />
    </form>
  </div>
</template>

<script>
import {
  SearchCustomer,
  TextArea,
  Input,
  DatePicker,
  SubmitButton,
} from "../../../components/Form/Index.js";

export default {
  components: {
    SearchCustomer,
    TextArea,
    Input,
    DatePicker,
    SubmitButton,
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
      return {
        customer_id: null,
        amount: null,
        method: null,
        reference: null,
        date: null,
        internal_note: null,
      };
    },
  },
};
</script>
