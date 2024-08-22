<template>
  <AppHead :title="data.title" />
  <div class="container page-container">
    <div class="row">
      <div class="col-md-12">
        <h4>{{ __("Income Statement") }}</h4>
        <hr />
      </div>
    </div>
    <div class="row">
      <div class="col-md-3">
        <form
          @submit.prevent="
            form.get(route('admin.reports.income.statement'), formConfig)
          "
        >
          <DateRangePicker
            v-model="form.date_range"
            :label="__('Date Range')"
          />
          <SubmitButton :disabled="form.processing" />
        </form>
      </div>

      <div class="col-md-9">
        <div v-if="data.report_generated && !form.processing">
          <div id="report-1">        
            <div class="border p-4 mt-4">
              <div class="text-muted text-center"
              ><span class="text-danger">*</span>
              {{
                __(
                  "Report generated based on Invoices and Bills from Authors"
                )
              }}</div
            >
              <h5 class="text-center">{{ __("Income Statement") }}</h5>
              <div class="text-center">
                {{ __("From") }} {{ data.from }} {{ __("to") }} {{ data.to }}
              </div>

              <table class="table table-sm">
                <tbody>
                  <tr>
                    <td>+ {{ __("Sales Revenue") }}</td>
                    <td class="text-end">
                      {{ formatMoney(data.income_statement_1_sales_revenue) }}
                    </td>
                  </tr>

                  <tr>
                    <td>- {{ __("Sales Tax") }}</td>
                    <td class="text-end">
                      {{
                        formatMoney(
                          data.income_statement_1_sales_tax_amount
                        )
                      }}
                    </td>
                  </tr>
                  <tr>
                    <td>- {{ __("Payment to Authors") }}</td>
                    <td class="text-end">
                      {{
                        formatMoney(
                          data.income_statement_1_total_payment_to_authors
                        )
                      }}
                    </td>
                  </tr>
                  <tr>
                    <th>{{ __("Net Income") }}</th>
                    <th class="text-end">
                      {{ formatMoney(data.income_statement_1_net_income) }}
                    </th>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <div id="report-2" class="mt-4">            
            <div class="border p-4 mt-4">
              <div class="text-muted text-center"
              ><span class="text-danger">*</span>
              {{
                __(
                  "Report generated based on all the tasks that are marked Complete"
                )
              }}</div
            >
              <h5 class="text-center">{{ __("Income Statement") }}</h5>
              <div class="text-center">
                {{ __("From") }} {{ data.from }} {{ __("to") }} {{ data.to }}
              </div>

              <table class="table table-sm">
                <thead>
                  <tr>
                    <th scope="col" style="width: 40%">
                      {{ __("Sales Revenue") }}
                    </th>
                    <th scope="col" class="text-end"></th>
                  </tr>
                </thead>
                <tbody>
                  <tr
                    v-for="(task, index) in data.income_statement_2_records"
                    :key="index"
                  >
                    <td>+ {{ task.service.name }}</td>
                    <td class="text-end">
                      {{ formatMoney(task.total) }}
                    </td>
                  </tr>
                  <tr>
                     <th>{{ __('Total Sales Revenue') }}</th>
                     <th class="text-end">{{ formatMoney(data.income_statement_2_sales_revenue) }}</th>
                  </tr>
                </tbody>
              </table>
              <table class="table table-sm">
                <thead>
                  <tr>
                    <th scope="col" style="width: 40%">
                       {{ __("Expenses") }}
                    </th>
                    <th scope="col" class="text-end"></th>
                  </tr>
                </thead>
                <tbody>                 
                  <tr>
                    <td>- {{ __("Payment to Authors") }}</td>
                    <td class="text-end">
                      {{
                        formatMoney(
                          data.income_statement_2_total_payment_to_authors
                        )
                      }}
                    </td>
                  </tr>
                  <tr>
                    <th>{{ __("Net Income") }}</th>
                    <th class="text-end">
                      {{ formatMoney(data.income_statement_2_net_income) }}
                    </th>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div v-if="form.processing" class="text-center">
          {{ __("Generating report") }} ...
        </div>
      </div>
    </div>
  </div>
</template>
  
  <script>
import {
  DateRangePicker,
  SubmitButton,
} from "../../../components/Form/Index.js";

export default {
  props: ["data"],
  components: {
    DateRangePicker,
    SubmitButton,
  },
  created() {
    if (this.data?.date_range) {
      this.form.date_range = this.data.date_range;
    }
  },

  data() {
    return {
      form: this.$inertia.form({
        date_range: "",
      }),
      formConfig: {
        preserveState: true,
        preserveScroll: true,
        replace: true,
      },
    };
  },
};
</script>