<template>
  <AppHead :title="data.title" />
  <div class="container page-container">
    <PageTitle :title="data.title">
      <Link
        class="btn btn-sm btn-light"
        :href="route('customer.payments.index')"
      >
        <i class="fa-solid fa-arrow-left-long"></i> {{ __("Back to payments") }}
      </Link>
    </PageTitle>
    <div class="row">
      <div class="col-md-9">
        <div class="document-parent-container">
          <div class="document-container" style="min-height:auto;">
            <h3 class="text-center">{{ __("Payment Receipt") }}</h3>
            <div class="row fs-7">
              <div class="col-md-6">
                {{ __("Date") }} : {{ localDate(payment.created_at) }}
              </div>
              <div class="col-md-6 text-end text-bold">
                {{ __("Number") }} : {{ payment.number }}
              </div>
            </div>

            <table class="table table-sm fs-8 mt-4">
              <tbody>
                <tr>
                  <td>{{ __("Received From") }}</td>
                  <td>
                    <div>{{ payment.from.full_name }}</div>
                    <small class="text-muted">{{ payment.from.email }}</small>
                  </td>
                </tr>
                <tr>
                  <td>{{ __("Amount") }}</td>
                  <td>{{ formatMoney(payment.amount) }}</td>
                </tr>
                <tr>
                  <td>{{ __("Payment Method") }}</td>
                  <td>{{ payment.method }}</td>
                </tr>
                <tr>
                  <td>{{ __("Reference") }}</td>
                  <td>{{ payment.reference }}</td>
                </tr>
                <tr>
                  <td>{{ __("Reason") }}</td>
                  <td>{{ __("Wallet Top Up") }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="d-grid gap-2">
          <a
            class="btn btn-sm btn-outline-secondary"
            :href="route('customer.payments.download', payment.uuid)"
          >
            <i class="fa-solid fa-cloud-arrow-down"></i> {{ __("Download") }}
          </a>
        </div>
      </div>
    </div>
  </div>
</template>
      
  <script>
export default {
  props: ["data", "payment"],
};
</script>