<template>
  <AppHead :title="data.title" />

  <div class="container page-container">
    <PageTitle :title="data.title"><Link
        class="btn btn-sm btn-light"
        :href="route('admin.walletAdjustments.index')"
      >
        <i class="fa-solid fa-arrow-left-long"></i> {{ __("Back to Wallet Adjustments") }}
      </Link>
    </PageTitle>
    <div class="row">
      <div class="col-md-12">
        <table class="table table-striped-columns">
          <tbody>
            <tr>
              <th class="col-md-4">{{ __("Number") }}</th>
              <td>{{ wallet_adjustment.number }}</td>
            </tr>
            <tr>
              <th class="col-md-4">{{ __("Date") }}</th>
              <td>{{ localDate(wallet_adjustment.created_at) }}</td>
            </tr>
            <tr>
              <th class="col-md-4">{{ __("Type") }}</th>
              <td>{{ wallet_adjustment.type }}</td>
            </tr>
            <tr>
              <th class="col-md-4">{{ __("Account Holder") }}</th>
              <td>
                <Link
                  :href="
                    route('admin.customers.show', wallet_adjustment.user.uuid)
                  "
                  >{{ wallet_adjustment.user.full_name }}</Link
                >
              </td>
            </tr>
            <tr>
              <th class="col-md-4">{{ __("Description") }}</th>
              <td>{{ wallet_adjustment.description }}</td>
            </tr>
            <tr>
              <th class="col-md-4">{{ __("Amount") }}</th>
              <td>
                {{ formatMoney(wallet_adjustment.amount) }}
              </td>
            </tr>
            <tr>
              <th class="col-md-4">{{ __("Adjusted By") }}</th>
              <td>
                <Link
                  :href="
                    route('admin.users.show', wallet_adjustment.adjuster.uuid)
                  "
                  >{{ wallet_adjustment.adjuster.full_name }}</Link
                >
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>
        
    <script>
export default {
  props: ["data", "wallet_adjustment"],
};
</script>