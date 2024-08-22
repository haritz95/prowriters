<template>
  <div :class="$store.getters.constants.style.formGroup">
    <label :class="$store.getters.constants.style.formLabel">{{
      __("Urgency")
    }}</label>
    
      <v-select
        :reduce="(urgency) => urgency.id"
        v-model="$store.state.form.urgency_id"
        :options="$store.state.data.urgencies"
        label="name"
        :clearable="false"        
      ></v-select>     
    
    <ErrorField :name="'urgency_id'"></ErrorField>
  </div>
</template>

<script>
export default {
 inject: ["$store"]
};
</script>