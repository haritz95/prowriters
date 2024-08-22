<template>
  <TaskShowLayout :task="task" :activeTab="'financial'">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div v-if="task.invoice" class="d-flex justify-content-between border mb-4 p-2">
          <div>{{ __("Invoice Number") }}</div>
          <div>
            <Link :href="route('admin.invoices.show', task.invoice.uuid)">{{
              task.invoice.number
            }}</Link>
          </div>
        </div>

        <div class="border">
          <table class="table table-bordered table-sm" v-if="data.financial">
            <thead>
              <tr>
                <th>{{ __("Rate") }}</th>
                <th class="text-center">{{ __("Quantity") }}</th>
                <th class="text-end">{{ __("Total") }}</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="align-bottom">
                  <table class="table fs-8">
                    <tr v-for="(row, index) in data.financial.meta" :key="index">
                      <td>{{ row.label }}</td>
                      <td class="text-end">{{ formatMoney(row.value) }}</td>
                    </tr>
                    <tr>
                      <td>{{ __("Additional Services Price") }}</td>
                      <td class="text-end">
                        {{ formatMoney(task.additional_services_price) }}
                      </td>
                    </tr>
                  </table>
                  <div class="d-flex justify-content-between border-top align-bottom">
                    <th>{{ __("Final Rate") }}</th>
                    <th class="text-end">
                      {{
                        formatMoney(
                          parseFloat(data.financial.fields.amount) +
                            parseFloat(task.additional_services_price)
                        )
                      }}
                    </th>
                  </div>
                </td>
                <th class="text-center align-bottom">
                  {{ data.financial.fields.quantity }}
                </th>
                <th class="text-end align-bottom">
                  {{ formatMoney(task.total) }}
                  <div v-if="task.is_total_overridden">
                    <small class="text-danger">{{ __("Manually updated") }}</small>
                  </div>
                </th>
              </tr>
            </tbody>
          </table>

          <h5 class="text-center text-success">{{ __("Profit Calculation") }}</h5>
          <table class="table table-bordered table-sm">
            <thead>
              <tr>
                <th class="text-end">{{ __("Order Total") }}</th>
                <th class="text-end">{{ __("Author Payment") }}</th>
                <th class="text-end">{{ __("Profit") }}</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="text-end">{{ formatMoney(task.total) }}</td>
                <td class="text-end">{{ formatMoney(task.author_payment_amount) }}</td>
                <td class="text-end">
                  {{ formatMoney(task.total - task.author_payment_amount) }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </TaskShowLayout>
</template>

<script>
import TaskShowLayout from "../Partials/TaskShowLayout.vue";
import AcademicWritingFinancial from "./AcademicWritingFinancial.vue";
import ContentWritingFinancial from "./ContentWritingFinancial.vue";
import ResumeWritingFinancial from "./ResumeWritingFinancial.vue";

export default {
  props: ["data", "task"],
  components: {
    TaskShowLayout,
    AcademicWritingFinancial,
    ContentWritingFinancial,
    ResumeWritingFinancial,
  },
  data() {
    return {};
  },
};
</script>
