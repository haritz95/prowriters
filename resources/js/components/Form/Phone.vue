<template>
  <div class="mb-3">
    <label class="form-label"
      >{{ label }}
      <small v-if="note" class="text-muted">{{ note }}</small>
      <span v-if="required" class="ms-1 required">*</span>
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
    <vue-tel-input
      v-bind="propsBindings"
      v-model="phone"
      @input="$emit('update:modelValue', $event.target.value)"
    ></vue-tel-input>
    <ValidationError :name="name" />
  </div>
</template>

<script>
import { VueTelInput } from "vue-tel-input";
import "vue-tel-input/dist/vue-tel-input.css";

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
  ],
  emits: ["update:modelValue"],
  components: {
    VueTelInput,
  },
  mounted() {
    if (this.tooltip) {
      new Tooltip(this.$refs.tooltip);
    }
  },
  data() {
    return {
      phone : this.modelValue,
      propsBindings: {
        inputOptions: {
          styleClasses: "form-control form-control-sm",
        },        
      },
    };
  },
};
</script>