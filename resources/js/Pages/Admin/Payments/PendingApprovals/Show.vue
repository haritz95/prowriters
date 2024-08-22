<template>
  <AppHead :title="data.title" />
  <div class="container page-container">
    <PageTitle :title="data.title">
      <Link
        class="btn btn-sm btn-light"
        :href="route('admin.payments.pendingApprovals.index')"
      >
        <i class="fa-solid fa-arrow-left-long"></i>
        {{ __("Back") }}
      </Link>
    </PageTitle>
    <div class="row">
      <div class="col-md-8">
        <div class="document-parent-container">
          <div class="document-container" style="min-height: auto">
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
                  <td>{{ payment.method.name }}</td>
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

      <div class="col-md-4">
        <button
          class="btn btn-sm btn-success me-2"
          @click="approve(payment.uuid)"
        >
          <i class="far fa-thumbs-up"></i> {{ __("Approve") }}
        </button>
        <button class="btn btn-sm btn-danger" @click="disapprove(payment.uuid)">
          <i class="far fa-thumbs-down"></i> {{ __("Disapprove") }}
        </button>
      </div>
    </div>
  </div>
</template>
        
    <script>
export default {
  props: ["data", "payment"],
  methods: {
    approve(uuid) {
      let url = route("admin.payments.pendingApprovals.approve", uuid);
      let scope = this;
      this.confirmDialog(scope.__("Yes, Approve"), () => {
        scope.$inertia.get(url, scope.inertiaConfig);
      });
    },
    disapprove(uuid) {
      let scope = this;
      let url = route("admin.payments.pendingApprovals.disapprove", uuid);
      this.confirmDialog(scope.__("Yes, Disapprove"), () => {
        scope.$inertia.get(url, scope.inertiaConfig);
      });
    },
  },
};
</script>