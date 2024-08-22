<template>
  <div class="mb-3">
    <label class="form-label"
      >{{ label }} 
      <small v-if="note" class="text-muted">{{ note }}</small>
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
    <textarea
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
      :rows="rows ? rows : 4"
      class="form-control form-control-sm"
      :class="{ 'is-invalid': $page.props.errors[name] }"
      :placeholder="placeholder"
      @focus="$page.props.errors[name] = null"
    ></textarea>
    <ValidationError :name="name" />
  </div>
</template>

<script>
export default {
  props: [
    "modelValue",
    "label",
    "name",
    "placeholder",
    "required",
    "rows",
    "note",
    "tooltip",
  ],
  emits: ["update:modelValue"],
  mounted() {
    if (this.tooltip) {
      new Tooltip(this.$refs.tooltip);
    }
  },
};
</script>