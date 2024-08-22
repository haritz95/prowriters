<template>
  <div class="shadow-sm p-3 mb-5 bg-white rounded sticky-top">
    <div class="text-center mb-4">{{ __('Service') }} : <span class="text-muted"> {{ data.service.name }}</span> </div>
    
    <div class="text-center">
      <h5>{{ __("Total") }}</h5>
      <h5>{{ formatMoney(total) }}</h5>
    </div>

    <div v-if="data.is_admin">
      <SearchCustomer
        v-model="form.customer_id"
        :label="__('Customer')"
        name="customer_id"
        :options="data.dropdowns.customers"
      />
      <CheckBox
        v-model="form.is_total_overridden"
        name="is_total_overridden"
        :label="__('Override Total')"
      />
      <Input
        v-if="form.is_total_overridden"
        v-model="form.updated_total"
        :label="__('Enter updated Total')"
        @keypress="onlyNumber($event, form.updated_total)"
        name="updated_total"
      />
      <div class="d-grid gap-2">
        <button
          type="button"
          class="btn btn-outline-primary"
          v-on:click.prevent="submit()"
        >
          <span v-if="data.is_edit_on_mode">
            <i class="fa-solid fa-pen-to-square"></i> {{ __("Update") }}
          </span>
          <span v-else> <i class="fa-solid fa-plus"></i> {{ __("Create Task") }} </span>
        </button>
      </div>
    </div>

    <div v-else>
      <div class="d-grid gap-2">
        <button type="button" class="btn btn-success" v-on:click.prevent="submit()">
          <i class="fa-solid fa-cart-plus"></i> {{ __("Add to cart") }}
        </button>

        <button
          v-if="!data.is_edit_on_mode && data.dropdowns.customer_is_allowed_to_pay_later"
          type="button"
          class="btn btn-outline-primary"
          v-on:click.prevent="payLater()"
        >
          <i class="fa-solid fa-money-bill-wave"></i> {{ __("Pay Later") }}
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import { Input, CheckBox, SearchCustomer } from "../../components/Form/Index.js";

export default {
  props: ["data", "total", "form"],
  components: {
    Input,
    CheckBox,
    SearchCustomer,
  },
  methods: {
    submit() {
      if (this.data.is_edit_on_mode && this.data.is_admin) {
        this.form.patch(this.data.urls["add_to_cart"]);
      } else {
        this.form.post(this.data.urls["add_to_cart"], {
          preserveScroll: true,
          preserveState: true,
        });
      }
    },
    sendRequestForBid() {
      this.$inertia.post(this.data.urls["request_for_bid"], this.form);
    },
    payLater() {
      this.$inertia.post(this.data.urls["pay_later"], this.form);
    },
  },
};
</script>
