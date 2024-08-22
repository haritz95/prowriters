<template>
  <div class="document-parent-container">
    <div class="document-container">
      <!-- Badge overlay DIV -->
      <div class="ribbon-overlay">
        <span class="top-left ribbon" :style="{ background: invoice.status.bg_color }">
          {{ invoice.status.name }}
        </span>
      </div>

      <!-- Badge overlay DIV -->
      <div class="row">
        <div class="col-md-12 text-end mb-4">
          <h4 class="bold">
            {{ __("INVOICE") }} #
            <span>{{ invoice.number }}</span>
          </h4>
          <div>
            <div>{{ __("Created") }} : {{ localDate(invoice.invoice_date) }}</div>
            <div>{{ __("Due") }} : {{ localDate(invoice.due_date) }}</div>
          </div>
        </div>

        <div class="col-md-6">
          <strong>{{ __("Bill To") }} :</strong>

          <address>
            <div>
              {{ invoice.customer.full_name }}
            </div>
            <div>{{ invoice.customer.email }}</div>
          </address>
        </div>
        <div class="col-md-6 text-end">
          <div>
            <b>{{ __("Bill From") }} :</b>
          </div>
          <address>
            <div>{{ data.company.name }}</div>
            <div class="nl2br" v-html="data.company.address"></div>
          </address>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <table class="table mt-4">
            <thead>
              <tr>
                <th>#</th>
                <th class="description" width="50%">
                  {{ __("Item") }}
                </th>
                <th class="text-end">{{ __("Quantity") }}</th>
                <th class="text-end">{{ __("Rate") }}</th>
                <th class="text-end">{{ __("Sub Total") }}</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item, index) in invoice.items" :key="index">
                <td>{{ index + 1 }}</td>
                <td class="description">
                  <strong>{{ item.name }}</strong>
                  <span v-if="item.invoiceable">
                    ({{ data.invoiceable_types[item.invoiceable_type] }}

                    <Link :href="route(data.link_to_invoiceable_type[item.invoiceable_type], item.invoiceable.uuid)">{{
                      item.invoiceable.number
                    }}</Link>
                    )
                  </span>
                  <br />
                  <div class="text-color-light">{{ item.description }}</div>
                </td>
                <td class="text-end">{{ item.quantity }}</td>
                <td class="text-end">{{ formatMoney(item.price) }}</td>
                <td class="text-end">
                  {{ formatMoney(item.sub_total) }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="col-md-4 offset-md-8">
          <table class="table table-sm text-end">
            <tbody>
              <tr class="fw-bold">
                <td>
                  <span class="bold">{{ __("Sub Total") }}</span>
                </td>
                <td class="subtotal">{{ formatMoney(invoice.sub_total) }}</td>
              </tr>
              <tr class="fw-bold">
                <td>
                  <span class="bold">{{ __("Discount") }}</span>
                </td>
                <td class="subtotal">{{ formatMoney(invoice.discount) }}</td>
              </tr>
              <tr class="fw-bold" v-if="invoice.coupon_discount">
                <td>
                  <span class="bold">{{ __("Discount Coupon") }}</span>
                  <small v-if="invoice.coupon_code" class="text-muted">
                    ({{ invoice.coupon_code }})
                  </small>
                </td>
                <td class="subtotal">{{ formatMoney(invoice.coupon_discount) }}</td>
              </tr>
              <tr class="fw-bold">
                <td>
                  <span class="bold">{{ __("Sales Tax") }}</span>
                  <small v-if="invoice.sales_tax_rate" class="text-muted">
                    ({{ Math.round(invoice.sales_tax_rate, 2) }}%)
                  </small>
                </td>
                <td class="subtotal">{{ formatMoney(invoice.sales_tax_amount) }}</td>
              </tr>

              <tr class="fw-bold">
                <td>
                  <span class="bold">{{ __("Total") }}</span>
                </td>
                <td class="subtotal">{{ formatMoney(invoice.total) }}</td>
              </tr>
              <tr class="fw-bold">
                <td>
                  <span class="bold">{{ __("Amount Paid") }}</span>
                </td>
                <td class="subtotal">{{ formatMoney(invoice.amount_paid) }}</td>
              </tr>
              <tr class="fw-bold">
                <td>
                  <span class="bold">{{ __("Due") }}</span>
                </td>
                <td class="subtotal">
                  {{ formatMoney(invoice.total - invoice.amount_paid) }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <div v-if="invoice.customer_note">
            <strong>{{ __("Note") }}</strong>
            <p v-html="invoice.customer_note"></p>
          </div>

          <div v-if="data.show_admin_note && invoice.admin_note">
            <strong>{{ __("Internal Note") }}</strong>
            <p v-html="invoice.admin_note"></p>
          </div>

          <div v-if="invoice.terms_and_conditions">
            <strong>{{ __("Terms and Conditions") }}</strong>
            <p v-html="invoice.terms_and_conditions"></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["data", "invoice"],
};
</script>
