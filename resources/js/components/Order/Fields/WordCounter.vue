<template>
  <div :class="$store.getters.constants.style.formGroup">
    <label :class="$store.getters.constants.style.formLabel">{{
      __("Quantity")
    }}</label>
    <div :class="$store.getters.constants.style.formElement">
      <div class="row g-3">
        <div class="col-md-6">
          <InputSpinner
            v-model="$store.state.form.quantity"
            :step="1"
            :min="$store.state.serviceModel.minimum_order_quantity"
          ></InputSpinner>
        </div>
        <div
          class="col-md-6"
          
        >
          <RadioButton
            :options="$store.state.data.units_for_writing_services"
            :default_value="$store.state.form.unit_name"
            v-model="$store.state.form.unit_name"
          ></RadioButton>
        </div>
        <small
          class="text-muted"
          v-if="
            $store.state.form.unit_name == 'page'
          "
        >
          {{ __("Approximately") }}
          {{
            $store.state.number_of_words_per_page * $store.state.form.quantity
          }}
          {{ __("words") }}
        </small>
      </div>
      <ErrorField :name="'quantity'"/>
    </div>
  </div>

 <div
    :class="$store.getters.constants.style.formGroup"
    v-if="$store.state.form.unit_name == 'page'
    "
  >
    <label :class="$store.getters.constants.style.formLabel">{{
      __("Spacing")
    }}</label>
 
      <RadioButton
        :options="$store.state.data.spacings_types"
        :default_value="$store.state.form.spacing_type_id"
        v-model="$store.state.form.spacing_type_id"
      ></RadioButton>
   
    <ErrorField :name="'spacing_type_id'"/>
  </div> 
</template>

<script>
import InputSpinner from "../../InputSpinner.vue";
import RadioButton from "../../RadioButton.vue";

export default {
  inject: ["$store"],
  components: {
    InputSpinner,
    RadioButton,
  },
};
</script>
