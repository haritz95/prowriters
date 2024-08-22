<template>
   <div class="row">
      <!-- <table class="table table-sm mt-4">
         <tr>
            <td colspan="2">
               <small class="text-muted">{{ __('Applied Coupons')}}</small>
               <ul class="list-group list-group-flush">
                  <li
                     class="list-group-item pt-1 pb-1"
                     v-for="(coupon, index) in []"
                     :key="index"
                     >
                     <div class="d-flex justify-content-between">
                        <div>{{ coupon.code }}</div>
                        <div>
                           <a class="text-danger" href="#" v-on:click.prevent="handleRemoveCoupon(coupon, index)"><i class="fas fa-minus-circle"></i></a>
                        </div>
                     </div>
                  </li>
               </ul>
            </td>
         </tr>
      </table> -->
      <div class="col-md-6">
        

         <div class="input-group">
            <input
               type="text"
               v-model="coupon_code"
               class="form-control form-control-sm"
               :placeholder="__('Coupon code')"
               />
           
               <button
                  type="submit"
                  class="btn btn-secondary btn-sm"
                  v-on:click="handleApplyCoupon"
                  >
               {{ __('Apply coupon')}}
               </button>
           
            <div v-if="couponErrorMessage" class="invalid-feedback d-block">
               {{ couponErrorMessage }}
            </div>
         </div>
      </div>
   </div>
</template>

<script>
export default {
  data() {
    return {
      coupon_code: null,
      couponErrorMessage: null,
    };
  },

  methods: {
    handleRemoveCoupon(coupon, index) {
      if (this.$store.getters.form.coupons.length > 0) {
        this.$store.dispatch("removeCoupon", index)
      }
    },
    handleApplyCoupon() {       
      var scope = this;
      if (this.coupon_code) {
        this.$store
          .dispatch("addCoupon", this.coupon_code)
          .then(function (response) {           
            scope.coupon_code = null;
            scope.couponErrorMessage = null;
            if (response.status == 1) {
              scope.couponErrorMessage = null;
            } else {
              scope.couponErrorMessage = response.message
                ? response.message
                : scope.__("Something went wrong!");
            }
          });
      }
    }    
  },
};
</script>