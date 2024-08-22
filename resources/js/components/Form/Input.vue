<template>
  <div class="mb-3">
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
    <input
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
      :type="type ? type : 'text'"
      class="form-control form-control-sm"
      :class="[{ 'is-invalid': $page.props.errors[name] }, customStyle]"
      :placeholder="placeholder"
      @focus="$page.props.errors[name] = null"
      :readonly="readonly ? readonly : false"
    />
    <ValidationError :name="name" />
  </div>
</template>

<script>
export default {
  props: [
    "modelValue",
    "label",
    "name",
    "type",
    "placeholder",
    "required",
    "note",
    "tooltip",
    "readonly",
    "customStyle",
  ],
  emits: ["update:modelValue"],
  // mounted() {
  //   if (this.tooltip) {
  //     new Tooltip(this.$refs.tooltip);
  //   }
  // },
};
</script>