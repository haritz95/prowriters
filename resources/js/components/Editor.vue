<template>
  <div v-bind:class="{ 'mb-3': label }">
    <label v-if="label" class="form-label"
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
    <QuillEditor
      v-bind:style="{ height: height ? height : '150' + 'px' }"
      v-model:content="model"
      contentType="html"
      theme="snow"
      toolbar="essential"
    />
    <ValidationError v-if="name" :name="name" />
  </div>
</template>
<script>
import { QuillEditor } from "@vueup/vue-quill";
import "@vueup/vue-quill/dist/vue-quill.snow.css";

export default {
  props: ["modelValue", "height", "label", "note", "required", "tooltip", "name"],
  components: {
    QuillEditor,
  },
  emits: ["update:modelValue"],
  computed: {
    model: {
      get() {
        if (
          this.modelValue == null ||
          this.modelValue == undefined ||
          this.modelValue == ""
        ) {
          return "";
        } else {
          return this.modelValue;
        }
      },
      set(value) {
        this.$emit("update:modelValue", value);
        this.$emit("change", value);
      },
    },
  },
};
</script>
