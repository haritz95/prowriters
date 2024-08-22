<template>
  <AppHead :title="data.title" />
  <div class="container page-container">
    <PageTitle :title="data.title">
        <div class="float-end">
        <Link class="btn btn-sm btn-light" :href="data.urls.previous_page">
        <i class="fa-solid fa-chevron-left"></i> {{ data.previous_link_text }}</Link>       
      </div>
    </PageTitle>
    <div class="row">
      <div class="col-md-9">
        <div class="document-parent-container">
          <div class="document-container">
            <!-- Badge overlay DIV -->
            <div class="badge-overlay">
              <span class="top-left badge" :class="bill.paid ? 'green' : 'red'">
                {{ bill.paid ? __("Paid") : __("Unpaid") }}
              </span>
            </div>

            <!-- Badge overlay DIV -->
            <div class="row">
              <div class="col-md-12 text-end mb-3">
                <h4 class="bold">
                  {{ __("BILL") }}#
                  <span>{{ bill.number }}</span>
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
                  <div class="text-break" v-html="data.company.company_address"></div>
                </address>
              </div>
              <div class="col-md-6 text-end">
                <div>
                  <b>{{ __("Bill From") }} :</b>
                </div>
                <address>
                  <div>
                    <b>{{ bill.name }}</b>
                  </div>                  
                  <div class="text-break" v-html="bill.address"></div>
                </address>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <p>
                  <span class="bold">
                    {{ __("Date") }} : {{ localDate(bill.created_at) }}
                  </span>
                </p>
              </div>
              <div class="col-md-6">
                {{ __("Invoice") }}# : {{ bill.invoice_number }}
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
                    <tr v-for="(item, index) in bill.items" :key="index">
                      <td>{{ index + 1 }}</td>
                      <td class="description text-left">
                        <strong>{{ item.task.service.name }}</strong>
                        
                        ( <Link :href="route('admin.tasks.show', item.task.uuid)">{{ item.task.number }}</Link> )
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
                      <td class="subtotal">{{ formatMoney(bill.total) }}</td>
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
                {{ bill.note }}
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-3">        
        <div v-if="bill.paid">
          <form
            @submit.prevent="
              unpaidForm.post(data.urls.submit_form_mark_as_unpaid, formConfig)
            "
          >
            <div class="d-grid gap-2">
              <button
                :disabled="unpaidForm.processing"
                type="submit"
                class="btn btn-light btn-lg"
              >
                <i class="far fa-check-circle"></i> {{ __("Mark as unpaid") }}
              </button>
            </div>
            <div class="border p-3 mt-5">
                <p class="text-center">
                <strong>{{ __("Payment Date") }}</strong>
                <div>{{ localDate(bill.paid) }}</div>
            </p>
            <p class="text-center">
                <strong>{{ __("Payment Reference") }}</strong>
                <div>{{ bill.payment_reference }}</div>
            </p>
            </div>
          </form>
        </div>
        <div v-else>
          <div class="card">
            <div class="card-body">
              <form
                @submit.prevent="
                  paidForm.post(data.urls.submit_form_mark_as_paid, formConfig)
                "
              >
                <Input
                  v-model="paidForm.payment_reference"
                  :label="__('Payment Reference')"
                  name="payment_reference"
                />
                <div class="d-grid gap-2">
                  <button
                    :disabled="paidForm.processing"
                    type="submit"
                    class="btn btn-success btn-sm"
                  >
                    <i class="far fa-check-circle"></i> {{ __("Mark as paid") }}
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Input } from "../../../components/Form/Index.js";

export default {
  components: {
    Input,
  },
  props: ["data", "bill"],

  data() {
    return {
      paidForm: this.$inertia.form({
        payment_reference: this.bill.payment_reference,
      }),
      unpaidForm: this.$inertia.form(),
      formConfig: {
        preserveScroll: false,
        onSuccess: () => this.form.reset(),
      },
    };
  },
};
</script>