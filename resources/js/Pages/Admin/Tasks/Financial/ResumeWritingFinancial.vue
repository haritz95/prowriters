<template>  
    <table class="table table-sm">
      <thead>
        <tr>
          <th>{{ __("Description") }}</th>
          <th class="text-end">{{ __("Price") }}</th>
        </tr>
      </thead>
      <tbody>
    
        <tr>
          <th>{{ __("Basic Price") }}</th>
          <th class="text-end">{{ formatMoney(task.details.basic_price) }}</th>
        </tr>
        <tr>
          <td>{{ __("Additional Services") }}</td>
          <td class="text-end">
            {{ formatMoney(task.additional_services_price) }}
          </td>
        </tr>
  
        <tr>
          <td>{{ __("Customer Service") }}</td>
          <td class="text-end">
            {{ formatMoney(task.service_level_price) }}
          </td>
        </tr>
        <tr v-if="task.is_total_overridden">
          <th>{{ __("Original Total") }}</th>
          <th class="text-end">{{ formatMoney(task.original_total) }}</th>
        </tr>
        <tr>
          <th>
            <span v-if="task.is_total_overridden">{{
              __("Updated Total")
            }}</span>
            <span v-else>{{ __("Total") }}</span>
          </th>
          <th class="text-end">{{ formatMoney(task.total) }}</th>
        </tr>
        <tr>
          <td>{{ __("Author Cost") }}</td>
          <td class="text-end">
            {{ formatMoney(task.author_payment_amount) }}
          </td>
        </tr>
        <tr>
          <th>{{ __("Profit") }}</th>
          <th class="text-end">
            {{ formatMoney(task.total - task.author_payment_amount) }}
          </th>
        </tr>
      </tbody>
    </table>
  </template>
    
    
    <script>
  export default {
    props: ["task"],
  };
  </script>
    