<template>
  <div class="mb-2">
    <label class="form-label">
      {{ label }}
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
    <Datepicker
      :format="date_picker_format"
      :inputClassName="'form-control form-control-sm'"
      v-model="model"
      :auto-apply="date_picker_format == 'yyyy-MM-dd'"
      :is-24="true"
      :preview-format="date_picker_format"
      :enable-time-picker="with_time_picker"
    />

    <ValidationError :name="name" />
  </div>
</template>

<script>
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";

export default {
  props: [
    "modelValue",
    "label",
    "name",
    "placeholder",
    "required",
    "note",
    "tooltip",       
    "with_time_picker",
  ],
  components: {
    Datepicker,
  },
  computed: {
    model: {
      get() {
        return this.modelValue;
        //return (Number.isNaN(Number(this.modelValue))) ? this.modelValue : Number(this.modelValue);
      },
      set(dateObject) {
        // let time = String(dateObject.getHours()).padStart(2, '0') + ":" + String(dateObject.getMinutes()).padStart(2, '0');
        // let date = dateObject.toISOString().split("T")[0];
        // console.log(date + " " + time);
        //this.$emit("update:modelValue", dateObject.getFullYear() + '-' + (dateObject.getMonth() + 1) + '-' + dateObject.getDate() );
        //this.$emit("update:modelValue", date + " " + time);
        // this.$emit("update:modelValue", dateObject.toISOString().split('T')[0]);
        this.$emit("update:modelValue", dateObject);
      },
    },
  },
  data() {
    return {
      date_picker_format: this.with_time_picker ? "yyyy-MM-dd HH:mm" : "yyyy-MM-dd",
    };
  },
};
</script>
