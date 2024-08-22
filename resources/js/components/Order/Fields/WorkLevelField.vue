<template>
  <div
    :class="$store.getters.constants.style.formGroup"
    v-if="
      $store.state.serviceModel.has_work_level &&
      $store.state.serviceModel.work_levels &&
      Object.values($store.state.serviceModel.work_levels).length
    "
  >
    <label :class="$store.getters.constants.style.formLabel">{{
      __("Work Level")
    }}</label>
    <div :class="$store.getters.constants.style.formElement">
      <v-select
        :reduce="(work_level) => work_level.id"
        v-model="$store.state.form.work_level_id"
        :options="$store.state.serviceModel.work_levels"
        label="name"
        :clearable="false"
        :searchable="false"
      ></v-select>
      <ErrorField :name="'work_level_id'"></ErrorField>
    </div>
  </div>
</template>

<script>
export default {
  inject: ["$store"]
};
</script>