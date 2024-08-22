<template>
  <div class="row">
    <div class="col-md-3">
      <div class="p-2 task-card border">
        <div
          v-for="(brief, index) in briefs.meta"
          class="border-bottom mb-2 pb-2"
          :key="index"
        >
          <small class="text-muted fw-bolder">{{ brief.label }}</small>
          <div class="">{{ brief.value }}</div>
        </div>

        <div
          v-for="(row, index) in commonBriefs"
          class="border-bottom mb-2 pb-2"
          :key="index"
        >
          <small class="text-muted fw-bolder">{{ row.label }}</small>
          <div class="">{{ row.value }}</div>
        </div>

        <div v-if="task.additional_services && task.additional_services.length > 0">
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
      </div>
    </div>

    <div class="col-md-9">
      <div class="border p-4">
        <div class="fw-bold">{{ __("Title") }}</div>
        <small>{{ task.title }}</small>
        <hr />

        <div class="mt-2 mb-2" v-for="(detail, index) in briefs.fields" :key="index">
          <div class="fw-bold">{{ detail.label }}</div>
          <small class="text-break" v-html="detail.value"></small>
          <hr />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["task", "briefs", "commonBriefs"],
};
</script>
