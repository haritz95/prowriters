<template>
  <div v-if="task.additional_services.length > 0">
    <div class="fw-bolder mt-4 mb-4">{{ __("Additional Services") }}</div>
    <div
      v-for="(service, index) in task.additional_services"
      class="border-bottom mb-2 pb-2"
      :key="index"
    >
      <div class="text-muted">{{ service.name }}</div>
      <table class="table table-sm">
        <tr>
          <td>
            <small class="text-muted">{{ __("Quantity") }} :</small>
          </td>
          <td class="text-end">
            <small class="text-muted">{{ service.pivot.quantity }}</small>
          </td>
        </tr>
      </table>
    </div>
  </div>
</template>

<script>
export default {
  props: ["task"],
};
</script>
