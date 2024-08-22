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
    <div class="input-group">
      <button
        type="button"
        class="btn btn-outline-secondary btn-minus"
        v-on:click="changeNumber(-step)"
      >
        <i class="fas fa-minus"></i>
      </button>

      <input
        type="number"
        class="form-control form-control-sm text-center"
        aria-describedby="basic-addon1"
        v-model="selectedOption"
        v-on:keypress="isNumber($event)"
        v-on:blur="onBlur($event)"
      />

      <button
        type="button"
        class="btn btn-outline-secondary btn-plus"
        v-on:click="changeNumber(step)"
      >
        <i class="fas fa-plus"></i>
      </button>
    </div>
  </div>
</template>
<script>
export default {
  props: ["modelValue", "min", "step", "allow_zero", "label", "tooltip", "required", "note"],
  mounted() {
    if (!this.modelValue) {
      this.selectedOption = this.min;
      this.triggerEmit();
    }
  },
  data() {
    return {
      old_value: this.modelValue ? this.modelValue : 1,
      selectedOption: this.modelValue ? this.modelValue : 1,
    };
  },
  watch: {
    modelValue: function (newValue, oldValue) {
      this.old_value = parseInt(newValue);
      this.selectedOption = parseInt(newValue);
    },
  },
  methods: {
    onBlur($event) {
      var newValue = parseInt(this.selectedOption);
      if (!newValue) {
        newValue = parseInt(this.old_value);
      }
      this.selectedOption = parseInt(newValue);
      this.triggerEmit();
    },
    changeNumber(changeByValue) {
      var changeByValue = parseInt(changeByValue);
      var quantity = parseInt(this.selectedOption);

      var thresholdLimit = this.allow_zero ? 0 : 1;

      if (quantity + changeByValue < thresholdLimit) {
        return false;
      }
      if (!Number.isInteger(changeByValue)) {
        return false;
      }
      var quantity = quantity + changeByValue;
      this.selectedOption = quantity;

      this.triggerEmit();
    },
    triggerEmit() {
      this.$emit("update:modelValue", this.selectedOption);
      this.$emit("updated", this.selectedOption);
    },

    isNumber: function (evt) {
      evt = evt ? evt : window.event;
      var charCode = evt.which ? evt.which : evt.keyCode;
      if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        evt.preventDefault();
      } else {
        return true;
      }
    },
  },
};
</script>

<style scoped>
.btn-outline-secondary {
  border-color: #ced4da;
  box-shadow: none !important;
  border: 1px solid #ced4da;
}

.btn-outline-secondary.btn-minus {
  border-top: 1px solid #ced4da;
  border-bottom: 1px solid #ced4da;
  border-left: 1px solid #ced4da;
}

.btn-outline-secondary.btn-minus:hover {
  background: #fff;
  color: #6c757d;
}
.btn-outline-secondary.btn-plus {
  border-top: 1px solid #ced4da;
  border-right: 1px solid #ced4da;
  border-bottom: 1px solid #ced4da;
  border-top-left-radius: 0px;
  border-bottom-left-radius: 0px;
}
.btn-outline-secondary.btn-plus:hover {
  background: #fff;
  color: #6c757d;
}
</style>
