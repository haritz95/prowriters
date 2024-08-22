<template>
  <label class="form-label" v-if="label"
    >{{ label }}
    <small v-if="note" class="text-muted me-1">{{ note }}</small>
    <span v-if="required" class="required">*</span>
    <span
      v-if="tooltip"
      class="ms-1"
      ref="tooltip"
      data-bs-toggle="tooltip"
      data-bs-placement="top"
      :title="tooltip"
      ><i class="fa-solid fa-circle-question"></i
    ></span>
  </label>
  <div class="btn-group d-flex" role="group" aria-label="Basic radio toggle button group">
    <template v-for="(row, index) in options" :key="index">
      <input
        type="radio"
        class="btn-check"
        :name="name"
        :id="'btnradio_' + row.id"
        autocomplete="off"
        v-bind:value="row.id"
        v-model="model"
      />
      <label class="btn btn-outline-primary" :for="'btnradio_' + row.id">{{
        row.name
      }}</label>
    </template>
  </div>

  <ValidationError :name="name" />
</template>
<script>
export default {
  props: ["modelValue", "options", "name", "required", "label", "tooltip"],
  emits: ["update:modelValue"],
  computed: {
    model: {
      get() {
        return this.modelValue;
      },
      set(value) {
        this.$emit("update:modelValue", value);
      },
    },
  },
};
</script>
