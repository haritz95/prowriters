<template>
  <AppHead :title="data.title" />
  <div class="container page-container">
    <PageTitle :title="data.title">
      <div class="float-end">
        <Link class="btn btn-sm btn-light" :href="route('author.paymentRequests.index')">
          <i class="fa-solid fa-chevron-left"></i>
          {{ data.previous_link_text }}</Link
        >
      </div>
    </PageTitle>
    <div class="row">
      <div class="col-md-9">
        <div class="document-parent-container">
          <div class="document-container">
            <h4><span class="badge rounded-pill text-bg-success" :class="paymentRequest.paid ? 'text-bg-success' : 'text-bg-danger'">{{ paymentRequest.paid ? __("Paid") : __("Unpaid") }}</span></h4>
            <div v-if="paymentRequest.paid && paymentRequest.reference">{{ __('Payment Reference') }} : {{ paymentRequest.reference }}</div>
            <div class="row">
              <div class="col-md-12 text-end mb-3">
                <h4 class="bold">
                  {{ __("BILL") }}#
                  <span>{{ paymentRequest.number }}</span>
                </h4>
              </div>

              <div class="col-md-6">
                <div>
                  <b>{{ __("Bill To") }} :</b>
                </div>
                <address>
                  <div>
                    <b>{{ data.company.company_name }}</b>
                  </div>
                  <div
                    class="text-break nl2br"
                    v-html="data.company.company_address"
                  ></div>
                </address>
              </div>
              <div class="col-md-6 text-end">
                <div>
                  <b>{{ __("Bill From") }} :</b>
                </div>
                <address>
                  <div>
                    <b>{{ paymentRequest.name }}</b>
                  </div>
                  <div class="text-break nl2br" v-html="paymentRequest.address"></div>
                </address>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <p>
                  <span class="bold">
                    {{ __("Date") }} :
                    {{ localDate(paymentRequest.created_at) }}
                  </span>
                </p>
              </div>
              <div class="col-md-6">
                {{ __("Invoice") }}# : {{ paymentRequest.invoice_number }}
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <table class="table mt-4">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th class="description text-left" width="50%">
                        {{ __("Item") }}
                      </th>
                      <th class="text-end">{{ __("Quantity") }}</th>
                      <th class="text-end">{{ __("Rate") }}</th>
                      <th class="text-end">{{ __("Sub Total") }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr
                      v-for="(item, index) in paymentRequest.items"
                      :key="index"
                    >
                      <td>{{ index + 1 }}</td>
                      <td class="description text-left">
                        <strong>{{ item.task.service.name }}</strong>

                        (
                        <Link
                          :href="route('author.tasks.show', item.task.uuid)"
                          >{{ item.task.number }}</Link
                        >
                        )
                        <br />
                        <span class="text-color-light">
                          {{ item.task.title }}
                        </span>
                      </td>
                      <td class="text-end">1</td>
                      <td class="text-end">{{ formatMoney(item.total) }}</td>
                      <td class="text-end">{{ formatMoney(item.total) }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="col-md-4 offset-md-8">
                <table class="table text-end">
                  <tbody>
                    <tr>
                      <td>
                        <span class="bold">{{ __("Total") }}</span>
                      </td>
                      <td class="subtotal">
                        {{ formatMoney(paymentRequest.total) }}
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <div>
                  <b>{{ __("Note") }}</b>
                </div>
                {{ paymentRequest.note }}
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <Link
          v-if="data.allow.archiving"
          class="btn btn-sm btn-outline-secondary me-2"
          :href="route('author.paymentRequests.archive', paymentRequest.uuid)"
          method="post"
          as="button"
          type="button"
          preserve-scroll
        >
          <i class="fa-solid fa-box-archive"></i> {{ __("Archive") }}
        </Link>

        <Link
          v-if="data.allow.unarchiving"
          class="btn btn-sm btn-outline-warning me-2"
          :href="route('author.paymentRequests.unarchive', paymentRequest.uuid)"
          method="post"
          as="button"
          type="button"
          preserve-scroll
        >
          <i class="fa-solid fa-box-open"></i> {{ __("Unarchive") }}
        </Link>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["data", "paymentRequest"],
};
</script>