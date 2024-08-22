<template>
  <div :class="$store.getters.constants.style.formGroup">
    <label :class="$store.getters.constants.style.formLabel">{{
      __("Subject")
    }}</label>
    
      <v-select
        :reduce="(subject) => subject.id"
        v-model="$store.state.form.subject_id"
        :options="$store.state.serviceModel.subjects"
        label="name"
        :clearable="false"
        :searchable="true"
      ></v-select>
      <ErrorField :name="'subject_id'"></ErrorField>
    
  </div>
</template>

<script>
export default {
  inject: ["$store"],
};
</script>