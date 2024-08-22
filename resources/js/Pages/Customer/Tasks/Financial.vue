<template>
  <TaskShowLayout :task="task" activeTab="financial">
   <div class="row">
    <div class="offset-md-3 col-md-6">
      <table class="table table-sm" v-if="data.invoice">
      <thead>
        <tr>
          <th class="description" width="50%">
            {{ __("Invoice") }}
          </th>
          <th class="text-end">{{ __("Total") }}</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="description">
            <div>
              <Link
                :href="route('customer.invoices.show', data.invoice.uuid)"
                >{{ data.invoice.number }}</Link
              >
            </div> 
          </td>
          <td class="text-end">
            {{ formatMoney(data.invoice.total) }}
          </td>
          <td class="text-end">
            <a
              class="btn btn-sm btn-outline-secondary"
              :href="route('public.invoices.download', data.invoice.uuid)"
            >
              {{ __("Download") }}
            </a>
          </td>
        </tr>
      </tbody>
    </table>
    <div v-else class="text-center text-danger">{{ __('Not invoiced') }}</div>
    </div>
   </div>
  </TaskShowLayout>
</template>
  
  <script>
import TaskShowLayout from "./Partials/TaskShowLayout.vue";

export default {
  props: ["task", "data"],
  components: {
    TaskShowLayout,
  },
};
</script>