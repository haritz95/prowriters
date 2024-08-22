<template>
  <div class="form-check" v-for="(row, index) in options" :key="'btnradio_' + row.id">
    <input
      v-model="model"
      v-bind:value="row.id"
      class="form-check-input"
      type="radio"
      :name="name"
      :id="'btnradio_' + row.id"
  
    />
    <label class="form-check-label" :for="'btnradio_' + row.id">
      {{ row.name }}
    </label>
  </div>
  <ValidationError :name="name" />
</template>
<script>
export default {
  props: ["modelValue", "options", "name"],
  emits: ["update:modelValue"],
  computed: {
    model: {
      get() {
        return this.modelValue ;
      },
      set(value) {
        this.$emit("update:modelValue", value);
      },
    },
  }, 

};
</script>
