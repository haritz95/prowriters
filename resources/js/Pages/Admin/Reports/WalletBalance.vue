<template>
  <AppHead :title="data.title" />
  <div class="container page-container">
    <div class="row">
      <div class="col-md-12">
        <h4>{{ __("Total Wallet Balance") }}</h4>
        <small class="text-muted">{{ __('The following amount is the summation of wallet balances of all the users.') }}</small>
         <!-- <small class="text-muted">{{ __('Balance in wallets are advance payments and they do not reflect in your income statement') }}</small>     -->
        <hr />
        <h5>{{ __('Total') }} : {{ formatMoney(data.balance) }}</h5>
      </div>
    </div>
  </div>
</template>
    
    <script>
export default {
  props: ["data"],
};
</script>