<template>
    <div :class="$store.getters.constants.style.formGroup">
    <label :class="$store.getters.constants.style.formLabel">{{ __("Type of Work") }} </label>

    <div
      class="btn-group d-flex"
      role="group"
      aria-label="Basic radio toggle button group"
    >
    <template v-for="(work_type, index) in $store.state.data.work_types[$store.state.form.service_id]" :key="index">
      <input
        type="radio"
        class="btn-check"
        :name="'btnradio_' + index"
        :id="'btnradio_' + index"
        autocomplete="off"  
        :value="work_type.id"
        v-model="$store.state.form.work_type_id"      
      />
      <label class="btn btn-outline-primary" :for="'btnradio_' + index">{{
       work_type.name
      }}</label>
    </template>   
    </div>
    <ErrorField :name="'work_type_id'"/>
  </div>
</template>

<script>
export default {
  inject: ["$store"]
};
</script>