<template>
  <AppHead :title="data.title" />
  <div class="container page-container">
    <PageTitle :title="data.title">
      <Link class="btn btn-sm btn-light" :href="route('admin.payments.index')">
        <i class="fa-solid fa-arrow-left-long"></i> {{ __("Back to payments") }}
      </Link>
    </PageTitle>
    <div class="row">
      <div class="col-md-9">
        <div class="document-parent-container">
          <div class="document-container" style="min-height: auto">
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
                    <Link
                      :href="route('admin.customers.show', payment.from.uuid)"
                      >{{ payment.from.full_name }}</Link
                    >                  
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
                <tr>
                  <td>{{ __("Internal Note") }}</td>
                  <td>{{ payment.internal_note }}</td>
                </tr>
                <tr v-if="payment.attachments.length > 0">
                  <td>{{ __("Attachments") }}</td>
                  <td>
                    <div class="dropdown">
                      <button
                        class="btn btn-secondary dropdown-toggle"
                        type="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                      >
                        {{ __("Download") }}
                      </button>
                      <ul class="dropdown-menu">
                        <li
                          v-for="(attachment, index) in payment.attachments"
                          :key="index"
                        >
                          <a
                            class="dropdown-item"
                            :href="
                              route('attachments.download', attachment.uuid)
                            "
                            >{{ attachment.display_name }}</a
                          >
                        </li>
                      </ul>
                    </div>
                  </td>
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
            :href="route('admin.payments.download', payment.uuid)"
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