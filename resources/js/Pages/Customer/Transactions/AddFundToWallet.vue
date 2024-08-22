<template>
  <AppHead :title="data.title" />
  <div class="container page-container">
    <PageTitle :title="data.title"> </PageTitle>
    <div class="row">
      <div class="offset-md-3 col-md-3 text-center mx-auto d-none d-md-block">
        <img
          :src="asset('images/undraw_savings_re_eq4w.svg')"
          class="img-fluid"
        />
      </div>
      <div class="col-md-6">
        <form
          @submit.prevent="
            form.post(route('customer.transactions.funds.store'))
          "
        >
          <Input
            @keypress="onlyNumber($event, form.amount)"
            :label="__('Enter Amount')"
            v-model="form.amount"
            name="amount"
            :required="true"
          />
          <div class="mb-4">
            <small
              >{{ __("Current Balance") }} : {{ formatMoney(data.balance) }}
            </small>
          </div>

          <SubmitButton
            :disabled="form.processing"
            :button_text="
              __('Choose payment option') +
              ` <i class='fa-solid fa-arrow-right-long'></i>`
            "
          />
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import { Input, SubmitButton } from "../../../components/Form/Index.js";

export default {
  props: ["data"],
  components: {
    Input,
    SubmitButton,
  },
  data() {
    return {
      form: this.$inertia.form({
        amount: "",
      }),
      toolbar: {
        title: this.data.title,
        // links: {
        //     create: {
        //         title: 'Create',
        //         url: '#',
        //     },
        //     previous_page: {
        //         title: 'Back',
        //         url: '#',
        //     },
        // },
        hide_save_button: false,
      },
    };
  },
};
</script>