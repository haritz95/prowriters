<template>
  <div :class="bottom_margin ? bottom_margin : 'mb-3'">
    <div class="form-check">
      <input
        :true-value="value ? value : 1"
        :false-value="false"
        v-model="model"
        class="form-check-input"
        type="checkbox"
        :id="name"
      />
      <label class="form-check-label" :for="name">
        {{ label }}
        <small v-if="note" class="text-muted">{{ note }}</small>
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
    </div>

    <ValidationError :name="name" />
  </div>
</template>

<script>
export default {
  props: [
    "modelValue",
    "value",
    "label",
    "name",
    "note",
    "tooltip",
    "bottom_margin",
  ],
  emits: ["update:modelValue"],
  computed: {
    model: {
      get() {
        return this.value ? this.modelValue : Number(this.modelValue);
      },
      set(value) {
        this.$emit("update:modelValue", value);
      },
    },
  }, 
};
</script>