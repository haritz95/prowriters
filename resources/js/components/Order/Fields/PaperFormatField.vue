<template>
  <div :class="$store.getters.constants.style.formGroup">
    <label :class="$store.getters.constants.style.formLabel">{{
      __("Paper Formats")
    }}</label>
   
      <v-select
        :reduce="(format) => format.id"
        v-model="$store.state.form.paper_format_id"
        :options="$store.state.data.paper_formats"
        label="name"
        :clearable="false"
        :searchable="false"
      ></v-select>
      <ErrorField :name="'paper_format_id'"></ErrorField>
    
  </div>
</template>

<script>
export default {
  inject: ["$store"],
};
</script>
