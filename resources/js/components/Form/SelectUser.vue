<template>
  <div
    :class="bottom_margin ? bottom_margin : 'mb-3'"
    @click="$page.props.errors[name] = null"
  >
    <label v-if="label" class="form-label"
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

    <v-select
      :multiple="multiple ? true : false"
      :reduce="(user) => (reduce_key ? user[reduce_key] : user.id)"
      v-model="model"
      :options="options"
      :label="reduce_name ? reduce_name : 'full_name'"
      :clearable="clearable ? true : false"
      :searchable="searchable ? true : false"
      :placeholder="placeholder ? placeholder : __('Select')"
      class="w-100"
      :filter="fuseSearch"
    >
      <template v-slot:option="user">
        <div class="d-flex">
          <div class="flex-shrink-0">
            <img
              class="avatar"
              :src="user.small_avatar"
              :alt="user.full_name"
              loading="lazy"
            />
          </div>
          <div class="flex-grow-1 ms-3">
            <div>{{ user.full_name }} ({{ user.code }})</div>
            <div>
              <small class="text-muted">{{ __("Email") }} : {{ user.email }}</small>
            </div>
          </div>
        </div>
      </template>
    </v-select>

    <ValidationError :name="name" />
  </div>
</template>

<script>
import Fuse from "fuse.js";

export default {
  props: [
    "modelValue",
    "label",
    "name",
    "options",
    "required",
    "note",
    "tooltip",
    "searchable",
    "placeholder",
    "reduce_key",
    "reduce_name",
    "clearable",
    "bottom_margin",
    "multiple",
  ],
  emits: ["update:modelValue", "change"],
  computed: {
    model: {
      get() {
        if (
          this.modelValue == null ||
          this.modelValue == undefined ||
          this.modelValue == ""
        ) {
          return "";
        } else if (Number.isNaN(Number(this.modelValue))) {
          // if the value is not a number, then it is a string
          return this.modelValue;
        } else {
          // if the value is a number, then it cast string to number
          return Number(this.modelValue);
        }
        //return (Number.isNaN(Number(this.modelValue))) ? this.modelValue : Number(this.modelValue);
      },
      set(value) {
        this.$emit("update:modelValue", value);
        this.$emit("change", value);
      },
    },
  },
  methods: {
    fuseSearch(options, search) {
      const fuse = new Fuse(options, {
        keys: ["first_name", "last_name", "code", "email"],
        shouldSort: true,
      });
      return search.length ? fuse.search(search).map(({ item }) => item) : fuse.list;
    },
  },
  // mounted() {
  //   if (this.tooltip) {
  //     new Tooltip(this.$refs.tooltip);
  //   }
  // },
};
</script>
