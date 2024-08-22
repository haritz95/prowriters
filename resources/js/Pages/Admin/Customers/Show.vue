<template>
  <AppHead :title="data.title" />
  <ProfileLayout :customer="customer">

    <div class="alert alert-primary" role="alert" v-if="customer.customer_profile.internal_note">
      {{  customer.customer_profile.internal_note  }}
</div>

    <div class="row">
            <div class="col-md-6">
              <div class="border mb-4 mb-xl-0 p-4">
                <div class="">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">{{ __('Total Orders') }}</h5>
                      <span class="h3 font-weight-bold mb-0">{{ customer.customer_tasks_count  }}</span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                        <i class="fa-solid fa-book"></i>
                      </div>
                    </div>
                  </div>
                  <!-- <p class="mt-3 mb-0 text-muted text-sm">
                    <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                    <span class="text-nowrap">Since last month</span>
                  </p> -->
                </div>
              </div>
            </div>
            <!-- <div class="col-md-4">
              <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">Tasks in progress</h5>
                      <span class="h2 font-weight-bold mb-0">2,356</span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                        <i class="fas fa-chart-pie"></i>
                      </div>
                    </div>
                  </div>
                 <p class="mt-3 mb-0 text-muted text-sm">
                    <span class="text-danger mr-2"><i class="fas fa-arrow-down"></i> 3.48%</span>
                    <span class="text-nowrap">Since last week</span>
                  </p>
                </div>
              </div>
            </div> -->
            <div class="col-md-6">
              <div class="border mb-4 mb-xl-0 p-4">
                <div class="">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">{{ __('Wallet Balance') }}</h5>
                      <span class="h3 font-weight-bold mb-0">{{ formatMoney(customer.wallet_balance) }}</span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-success text-white rounded-circle shadow">
                        <i class="fa-solid fa-wallet"></i>
                      </div>
                    </div>
                  </div>
                  <!-- <p class="mt-3 mb-0 text-muted text-sm">
                    <span class="text-warning mr-2"><i class="fas fa-arrow-down"></i> 1.10%</span>
                    <span class="text-nowrap">Since yesterday</span>
                  </p> -->
                </div>
              </div>
            </div>
         
          </div>

    <!-- Page content -->


    <table class="table table-bordered mt-4">
      <tr>
        <td class="text-muted text-left">{{ __("Email") }}</td>
        <td class="text-left">{{ customer.email }}</td>
      </tr>
      <tr>
        <td class="text-muted text-left">{{ __("Phone") }}</td>
        <td class="text-left">{{ customer.phone }}</td>
      </tr>
      <tr>
        <td class="text-muted text-left">{{ __("Joined") }}</td>
        <td class="text-left">{{ localDateTime(customer.created_at) }}</td>
      </tr>
      <tr>
        <td class="text-muted text-left">{{ __("Last Login") }}</td>
        <td class="text-left">{{ localDateTime(customer.last_login_at) }}</td>
      </tr>
      <tr>
        <td class="text-muted text-left">{{ __("Country") }}</td>
        <td class="text-left">{{ (customer.country) ? customer.country.name : '' }}</td>
      </tr>  
      <tr>
        <td class="text-muted text-left">{{ __("Allow Paying Later") }}</td>
        <td class="text-left">{{ (customer.customer_profile.allow_paying_later) ? __('Yes') : __('No')  }}</td>
      </tr>
      <!-- <tr v-if="customer.customer_profile.allow_paying_later">
        <td class="text-muted text-left">{{ __("Credit Limit") }}</td>
        <td class="text-left">{{ formatMoney(customer.customer_profile.credit_limit) }}</td>
      </tr> -->
    </table>
  </ProfileLayout>
</template>

<script>
import ProfileLayout from "./Partials/ProfileLayout.vue";
export default {
  props: ["data", "customer"],
  components: {
    ProfileLayout,
  },
};
</script>

<style>

.icon {
  width: 3rem;
  height: 3rem;
}

.icon i {
  font-size: 2.25rem;
}

.icon-shape {
  display: inline-flex;
  padding: 12px;
  text-align: center;
  border-radius: 50%;
  align-items: center;
  justify-content: center;
}

.icon-shape i {
  font-size: 1.25rem;
}
</style>