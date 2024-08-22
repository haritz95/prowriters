<template>
  <AppHead :title="data.title" />
  <div class="container page-container">
    <PageTitle :title="data.title" />
    <div v-if="data.items">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-md-12" v-if="!data.is_logged_in">
              <RegisterOrLogin />
            </div>
            <div class="col-md-12">
              <h5 class="card-title order-form-section-title">
                {{ __("Your order") }}
              </h5>
              <table class="table">
                <thead>
                  <tr>
                    <th></th>
                    <th>{{ __("Item") }}</th>
                    <th>{{ __("Price") }}</th>
                    <th>{{ __("Quantity") }}</th>
                    <th class="text-end">{{ __("Sub Total") }}</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in data.items" :key="index">
                    <td>
                      <button
                        @click="removeCartItem(index)"
                        class="btn btn-link"
                      >
                        <i class="fa-solid fa-circle-minus"></i>
                      </button>
                    </td>
                    <td>
                      <Link
                        :href="route('customer.tasks.create', { id: index })"
                        >{{ item.name }}</Link
                      >
                      <p class="text-muted">{{ item.title }}</p>
                    </td>
                    <td>{{ formatMoney(item.price) }}</td>
                    <td>{{ item.quantity }}</td>
                    <td class="text-end">{{ formatMoney(item.price) }}</td>
                  </tr>
                </tbody>
              </table>
            </div>

            <div class="col-md-8">
              <Link
                :href="data.links.create_order"
                class="btn btn-outline-success btn-sm"
                ><i class="fa-solid fa-plus"></i>
                {{ __("Add another task") }}</Link
              >
            </div>

            <div class="col-md-4 text-end">
              <div class="input-group" v-if="!form.coupon_code">
                <input
                  type="text"
                  class="form-control form-control-sm"
                  :placeholder="__('Coupon code')"
                  v-model="coupon_code"
                />

                <button
                  :disabled="!coupon_code"
                  @click="applyCoupon"
                  type="button"
                  class="btn btn-secondary btn-sm"
                >
                  {{ __("Apply coupon") }}
                </button>

                <div class="invalid-feedback d-block">
                  {{ coupon_error_message }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-7"></div>
        <div class="col-md-5">
          <table class="table table-striped">
            <tbody>
              <tr>
                <td>{{ __("Sub Total") }}</td>
                <td class="text-end">{{ formatMoney(data.sub_total) }}</td>
              </tr>
              <tr v-if="form.coupon_code">
                <td>
                  {{ __("Coupon") }} : {{ form.coupon_code }}
                  <a href="#" class="fs-8" @click.stop.prevent="removeCouponCode"
                    >[ x {{ __("Remove") }}]</a
                  >
                </td>
                <td class="text-end">{{ formatMoney(coupon_discount) }}</td>
              </tr>
              <tr v-if="data.sales_tax_information.enable_sales_tax">
                <td>{{ __("Sales Tax") }} ({{ Math.round(data.sales_tax_information.sales_tax_rate, 2) }}%)</td>
                <td class="text-end">
                  {{ formatMoney(data.sales_tax_information.sales_tax_amount) }}
                </td>
              </tr>
              <tr>
                <td>{{ __("Total") }}</td>
                <td class="text-end">
                  {{ formatMoney(data.total - coupon_discount) }}
                </td>
              </tr>
            </tbody>
          </table>
          <div class="d-grid gap-2">
            <button
              :disabled="!$page.props.is_user_logged_in || data.items.length == 0"
              @click="submitForm"
              type="button"
              class="btn btn-primary btn-sm"
            >
              {{ __("Proceed to payment") }}
              <i class="fa-solid fa-arrow-right-long"></i>
            </button>
            <div class="text-center" v-if="!$page.props.is_user_logged_in">
              {{ __("Please login to proceed") }}
            </div>
          </div>
        </div>
      </div>
    </div>
    <div v-else>
      {{ data.no_item_message }}
      <Link :href="data.links.create_order">{{ __("Create new order") }}</Link>
    </div>
  </div>
</template>

<script>
import RegisterOrLogin from "../../components/Order/RegisterOrLogin.vue";

export default {
  components: {
    RegisterOrLogin,
  },
  props: ["data"],
  data() {
    return {
      coupon_code: this.data.coupon ? this.data.coupon.code : null,
      coupon_discount: this.data.coupon
        ? parseFloat(this.data.coupon.discount_amount)
        : 0,
      coupon_error_message: null,
      form: {
        sales_tax_rate : 0,
        sales_tax_amount : 0,
        coupon_code: this.data.coupon ? this.data.coupon.code : null,
      },
    };
  },
  methods: {
    applyCoupon() {
      let scope = this;
      scope.coupon_error_message = null;
      axios
        .post(route("coupons.verify"), {
          coupon_code: this.coupon_code
        })
        .then(function (response) {
          if (response.data.status == 2) {
            scope.coupon_error_message = response.data.message;
          } else {
            scope.coupon_discount = response.data.amount;
            scope.form.coupon_code = scope.coupon_code;
          }
        });
    },
    removeCouponCode() {
      this.coupon_code = null;
      this.coupon_discount = null;
      this.form.coupon_code = null;
      axios.post(route("coupons.remove"));
    },
    submitForm() {
      this.$inertia.post(route("proceed_to_payment"), this.form);
    },
    removeCartItem(id) {
      this.$inertia.delete(route("cart.destroy.item", id), {
        preserveScroll: true,
      });
    },
  },
};
</script>