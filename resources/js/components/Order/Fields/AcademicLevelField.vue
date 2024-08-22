<template>
  <div :class="$store.getters.constants.style.formGroup">
    <label :class="$store.getters.constants.style.formLabel">{{
      __("Academic Level")
    }}</label>

    <v-select
      :reduce="(academic_level) => academic_level.id"
      v-model="$store.state.form.academic_level_id"
      :options="$store.state.data.academic_levels"
      label="name"
      :clearable="false"
      :searchable="false"
    ></v-select>
    <ErrorField :name="'academic_level_id'"></ErrorField>
  </div>
</template>

<script>
export default {
  inject: ["$store"],
};
</script>