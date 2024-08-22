<template>
  <div class="shadow-sm p-3 mb-5 bg-white rounded sticky-top">
    <Input
      :label="__('Enter your budget amount')"
      v-model="form.budget"
      name="budget"
      @keypress="onlyNumber($event, form.budget)"
    />

    <div class="d-grid gap-2">
      <button type="button" class="btn btn-outline-primary" v-on:click.prevent="submit()">
        <i class="fa-solid fa-plus"></i> {{ __("Request for bid") }}
      </button>
    </div>
  </div>
</template>

<script>
import Input from "../../components/Form/Input.vue";

export default {
  props: ["data", "form"],
  components: {
    Input,
  },

  methods: {
    submit() {
      this.$inertia.post(this.data.urls["request_for_bid"], this.form);
    },
  },
};
</script>
