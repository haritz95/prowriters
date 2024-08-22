<template>
  <div class="mb-3">
    <label class="form-label">{{ __("Package") }}</label>
    <v-select
      :reduce="(assignment) => assignment.id"
      v-model="$store.state.form.assignment_id"
      :options="$store.state.serviceModel.assignments"
      label="name"
      :clearable="false"
      :searchable="true"
    >
      <template #option="{ name, description }">
        <div>{{ name }}</div>
        <small class="text-muted">{{ description }}</small>
      </template>
    </v-select>

    <ErrorField :name="'assignment_id'" />
  </div>
</template>
  
  <script>
export default {
  inject: ["$store"],
};
</script>