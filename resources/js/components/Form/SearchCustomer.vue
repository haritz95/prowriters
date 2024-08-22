<template>
  <div
    :class="bottom_margin ? bottom_margin : 'mb-3'"
    @click="$page.props.errors[name] = null"
  >
    <label class="form-label">{{ label }} </label>
    <v-select
      v-model="model"
      :placeholder="__('Start typing to search for a customer')"
      :reduce="(option) => option.id"
      label="full_name"
      :filterable="false"
      :options="options"
      @search="onSearch"
    >
      <template v-slot:no-options="{ search, searching }">
        <template v-if="searching">
          {{ __("No results found for") }} <em>{{ search }}</em
          >.
        </template>
        <em v-else style="opacity: 0.5"
          >{{ __("Start typing to search for a customer") }}.</em
        >
      </template>

      <template v-slot:option="option">
        <div>{{ option.full_name }}</div>
        <small class="text-muted">{{ option.email }}</small>
      </template>
    </v-select>
    <ValidationError :name="name" />
  </div>
</template>
  
  <script>
import debounce from "lodash/debounce";
import { ref } from "vue";

export default {
  props: [
    "modelValue",
    "options",
    "label",
    "name",
    "required",
    "bottom_margin",
  ],
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
      },
    },
  },
  setup(props, context) {
    const options = ref(props.options ? props.options : []);

    return { options };
  },
  methods: {
    onSearch(search, loading) {
      if (search.length) {
        loading(true);
        this.search(loading, search, this);
      }
    },
    search: debounce((loading, search, vm) => {
      fetch(
        route("admin.customers.search") + `?q=${encodeURI(search)}`       
      ).then((res) => {
        res.json().then((json) => (vm.options = json.items));
        loading(false);
      });
    }, 350),
  },
};
</script>