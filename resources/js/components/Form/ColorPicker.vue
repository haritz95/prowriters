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

    <div class="input-group">
      <input
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        :class="{ 'is-invalid': $page.props.errors[name] }"
        :placeholder="placeholder"
        @focus="$page.props.errors[name] = null"
        type="text"
        class="form-control form-control-sm form-control-color"
      />
      <span
        ref="color_picker"
        class="input-group-text btn btn-sm btn-primary"
        @click="openColorPicker"
        >{{ __("Choose") }}</span
      >
    </div>
    <ValidationError :name="name" />
  </div>
</template>
  
  <script>
import Picker from "vanilla-picker/csp";
import "vanilla-picker/dist/vanilla-picker.csp.css";

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
  mounted() {
    // Initialize the picker
    this.picker = new Picker({
      parent: this.$refs.color_picker,
      editorFormat: "hex",
      popup: "left",
    });
    
    // on select update value
    let scope = this;
    this.picker.onDone = function (color) {
      scope.updateInput(color.hex);
    };
    // set initial value;
    if (this.modelValue) {
      this.picker.setColor(this.modelValue, true);
    }
  },
  data() {
    return {
      picker: null,
    };
  },

  methods: {
    updateInput(value) {
      this.$emit("update:modelValue", value);
    },
    openColorPicker() {
      this.picker.openHandler();
    },
  },
};
</script>