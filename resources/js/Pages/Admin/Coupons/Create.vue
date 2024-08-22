<template>
  <AppHead :title="data.title" />
  <div class="container page-container mt-4">
    <PageTitle :title="data.title">
      <Link class="btn btn-sm btn-light" :href="data.urls.previous_page">{{
        data.previous_link_text
      }}</Link>
    </PageTitle>

    <div class="row justify-content-center">
      <div class="col-md-8">
        <form
          @submit.prevent="
            existing_record
              ? form.patch(data.urls.submit_form)
              : form.post(data.urls.submit_form, formConfig)
          "
        >
          <div class="row">
            <div class="col-md-2">
              <div class="h5">{{ __("Step 1") }}</div>
            </div>
            <div class="col-md-10">
              <div class="bg-light pt-2 pb-2 pl-1 pr-1">
                <b>{{ __("General") }}</b>
              </div>
              <div class="mt-4">
                <div class="row">
                  <div class="col-md-6">
                    <Select
                      v-model="form.type"
                      :options="data.dropdowns.coupon_types"
                      :label="__('Type')"
                      name="type"
                      :required="true"
                    />
                  </div>
                </div>
                <Input
                  v-model="form.code"
                  name="code"
                  :label="__('Code')"
                  :required="true"
                  :placeholder="__('BlackFriday')"
                />

                <Input
                  v-model="form.description"
                  name="description"
                  :label="__('Description')"
                />

                <Input
                  v-model="form.amount"
                  name="amount"
                  :label="__('Amount')"
                  :tooltip="__('Value of the coupon')"
                  :required="true"
                  @keypress="onlyNumber($event, form.amount)"
                />

                <div class="row">
                  <div class="col-md-6">
                    <DatePicker
                      v-model="form.active_date"
                      name="active_date"
                      :label="__('Active Date')"
                      :tooltip="__('The date the coupon will be active')"
                      :required="true"
                    />
                  </div>

                  <div class="col-md-6">
                    <DatePicker
                      v-model="form.expiry_date"
                      name="expiry_date"
                      :label="__('Expiry Date')"
                      :tooltip="__('The date the coupon will expire')"
                    />
                    <small class="form-text text-muted">{{
                      __("Leave it blank for no expiry")
                    }}</small>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <hr />

          <div class="row mt-4">
            <div class="col-md-2">
              <div class="h5">{{ __("Step 2") }}</div>
            </div>
            <div class="col-md-10">
              <div class="bg-light pt-2 pb-2 pl-1 pr-1">
                <b>{{ __("Usage limits") }}</b>
              </div>
              <div class="mt-4">
                <div class="row">
                  <div class="col-md-6">
                    <Input
                      v-model="form.minimum_spend"
                      name="minimum_spend"
                      :label="__('Minimum Order Total')"
                      @keypress="onlyNumber($event, form.minimum_spend)"
                      :tooltip="
                        __(
                          'This field allows you to set the minimum spend (subtotal) allowed to use the coupon.'
                        )
                      "
                      :required="true"
                    />
                  </div>
                  <div class="col-md-6">
                    <Input
                      v-if="form.type == 'percentage'"
                      v-model="form.maximum_discount"
                      name="maximum_discount"
                      :label="__('Maximum Discount Amount')"
                      :tooltip="__('Maximum amount of discount at once')"
                      @keypress="onlyNumber($event, form.maximum_discount)"
                    />
                  </div>
                </div>
                <div class="row" v-if="!form.first_order_only">
                  <div class="col-md-6">
                    <Input
                      type="number"
                      v-model="form.usage_limit_per_coupon"
                      name="usage_limit_per_coupon"
                      :label="__('Usage limit per coupon')"
                      :tooltip="
                        __(
                          'How many times this coupon can be used before it is void.'
                        )
                      "
                      @keypress="onlyNumber($event, form.usage_limit_per_coupon)"
                    />
                    <small class="form-text text-muted">{{
                      __("Leave it blank for unlimited")
                    }}</small>
                  </div>
                  <div class="col-md-6">
                    <Input
                      type="number"
                      v-model="form.usage_limit_per_user"
                      name="usage_limit_per_user"
                      :label="__('Usage limit per user')"
                      :tooltip="
                        __(
                          'How many times this coupon can be used by an individual user'
                        )
                      "
                      @keypress="onlyNumber($event, form.usage_limit_per_user)"
                    />
                    <small class="form-text text-muted">{{
                      __("Leave it blank for unlimited")
                    }}</small>
                  </div>
                </div>

                <div class="mt-4">
                  <CheckBox
                    v-model="form.specific_customer_only"
                    name="specific_customer_only"
                    :label="__('Specific customer only')"
                  />
                </div>

                <SearchCustomer
                  v-if="form.specific_customer_only"
                  v-model="form.customer_id"
                  :label="__('Customer')"
                  name="customer_id"
                  :options="data.dropdowns.customers"
                />

                <div class="mt-4">
                  <CheckBox
                    v-model="form.first_order_only"
                    name="first_order_only"
                    :label="__('First order only')"
                    :tooltip="__('Customer can use it on the first order only')"
                  />
                </div>

                <div class="mt-4">
                  <CheckBox
                    v-model="form.inactive"
                    name="inactive"
                    :label="__('Inactive')"
                  />
                </div>
              </div>
            </div>
          </div>

          <div class="row mt-2">
            <div class="col-md-2"></div>
            <div class="col-md-10">
              <hr />
              <SubmitButton :disabled="form.processing" />
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import {
  Input,
  Select,
  SubmitButton,
  DatePicker,
  CheckBox,
  SearchCustomer,
} from "../../../components/Form/Index.js";

export default {
  components: {
    Input,
    Select,
    SubmitButton,
    DatePicker,
    CheckBox,
    SearchCustomer,
  },
  props: ["data", "existing_record"],
  watch : {
    'form.type' : function(newType){
       if(newType == 'fixed')
       {
        this.form.maximum_discount = null;
       }
    },
    'form.first_order_only' : function(newType){
       if(newType == true)
       {
        this.form.usage_limit_per_coupon = null;
        this.form.usage_limit_per_user = null;
       }
    }
  },
  data() {
    return {
      form: this.$inertia.form(this.prepareForm()),
      formConfig: {
        preserveScroll: true,
        onSuccess: () => this.form.reset(),
      },
    };
  },
  methods: {
    prepareForm() {
      let inputs = {
        type: null,
        code: null,
        description: null,
        amount: null,
        active_date: null,
        expiry_date: null,
        minimum_spend: null,
        maximum_discount: null,
        usage_limit_per_coupon: null,
        usage_limit_per_user: null,
        specific_customer_only: null,
        customer_id: null,
        first_order_only: null,
        inactive: null,
        archive: null,
      };
      if (this.existing_record) {
        inputs = { ...inputs, ...this.existing_record };
      }
      return inputs;
    },
  },
};
</script>