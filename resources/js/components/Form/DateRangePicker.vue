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
      range
      :presetRanges="presetRanges"
      :format="'yyyy-MM-dd'"
      :inputClassName="'form-control form-control-sm'"
      v-model="model"
    />

    <ValidationError :name="name" />
  </div>
</template>
  
  
  <script>
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';

import {
  endOfMonth,
  endOfYear,
  startOfMonth,
  startOfYear,
  subMonths,
  subDays,
} from "date-fns";

export default {
  props: [
    "modelValue",
    "label",
    "name",
    "placeholder",
    "required",
    "note",
    "tooltip",
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
      set(value) {
        this.$emit("update:modelValue", value);
      },
    },
  },
  data() {
    return {
      presetRanges: [
        { label: "Today", range: [new Date(), new Date()] },
        {
          label: "Yesterday",
          range: [subDays(new Date(), 1), subDays(new Date(), 1)],
        },
        { label: "Last 7 Days", range: [subDays(new Date(), 6), new Date()] },
        {
          label: "This month",
          range: [startOfMonth(new Date()), endOfMonth(new Date())],
        },
        {
          label: "Last month",
          range: [
            startOfMonth(subMonths(new Date(), 1)),
            endOfMonth(subMonths(new Date(), 1)),
          ],
        },
        {
          label: "This year",
          range: [startOfYear(new Date()), endOfYear(new Date())],
        },
      ],
    };
  },
};
</script>
  