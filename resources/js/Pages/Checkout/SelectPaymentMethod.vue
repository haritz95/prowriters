<template>
  <AppHead :title="data.title" />
  <CheckoutLayout :data="data" :title="__('Select a payment option')">
    <div>
      <div v-if="data.payment_options.online">
        <p class="text-muted">{{ __("Online") }}</p>
        <div class="list-group">
          <Link
            :key="index"
            v-for="(option, index) in data.payment_options.online"
            :href="option.url"
            class="list-group-item list-group-item-action"
          >
            {{ option.name }}
          </Link>
        </div>
      </div>
      <div v-if="data.payment_options.offline">
        <br />
        <p class="text-muted">{{ __("Offline") }}</p>
        <div class="list-group">
          <Link
            :key="index"
            v-for="(option, index) in data.payment_options.offline"
            :href="
              route('pay_with_offline_method', {
                payment_method: option.slug,
                token: data.token,
              })
            "
            class="list-group-item list-group-item-action"
          >
            <div>{{ option.name }}</div>
            <small class="text-muted">{{ option.description }}</small>
          </Link>
        </div>
      </div>
      <div v-if="data.show_wallet_option">
        <br />
        <p class="text-muted">
          {{ __("Wallet") }} {{ __("Balance") }} :
          {{ data.wallet_balance }}
        </p>
        <div class="list-group">
          <Link
            :href="route('pay_with_wallet', { token: data.token })"
            class="list-group-item list-group-item-action"
          >
            <div>
              <i class="fas fa-wallet"></i>
              {{ __("Pay using your wallet") }}
            </div>
          </Link>
        </div>     
      </div>
    </div>
  </CheckoutLayout>
</template>


<script>
import CheckoutLayout from "./CheckoutLayout.vue";

export default {
  props: ["data"],
  components: {
    CheckoutLayout,
  },
};
</script>