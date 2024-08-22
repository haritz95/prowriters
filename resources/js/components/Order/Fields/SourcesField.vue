<template>
  <div :class="$store.getters.constants.style.formGroup">
    <label :class="$store.getters.constants.style.formLabel">{{
      __("Sources")
    }}</label>
    <div :class="$store.getters.constants.style.formElement">
      <InputSpinner
        v-model="$store.state.form.number_of_sources"
        :step="1"
        :min="0"
        :allow_zero="true"
      />
      <ErrorField :name="'number_of_sources'" />
    </div>
  </div>
</template>

<script>
import InputSpinner from "../../InputSpinner.vue";

export default {
  inject: ["$store"],
  components: {
    InputSpinner,
  },
};
</script>
