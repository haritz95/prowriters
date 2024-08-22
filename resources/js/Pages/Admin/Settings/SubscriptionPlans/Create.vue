<template>
  <Modal :title="data.title">
    <form
      @submit.prevent="
        existing_record
          ? form.patch(
              route('admin.settings.subscriptionPlans.update', existing_record.uuid)
            )
          : form.post(route('admin.settings.subscriptionPlans.store'))
      "
    >
      <Input
        v-if="!form.is_free"
        v-model="form.stripe_id"
        name="stripe_id"
        :label="__('Stripe API ID')"
        :required="true"
        :tooltip="__('Collect it from your Stripe dashboard')"
      />

      <Input v-model="form.title" name="title" :label="__('Title')" :required="true" />

      <TextArea
        v-model="form.description"
        name="description"
        :label="__('Description')"
      />

      <Input
        v-model="form.number_of_characters_allowed_per_month"
        name="number_of_characters_allowed_per_month"
        :label="__('Number of characters allowed per month')"
        :required="true"
      />

      <Input
        v-if="!form.is_free"
        v-model="form.price"
        name="price"
        :label="__('Price')"
        :required="true"
      />

      <CheckBox v-model="form.is_free" :label="__('Free Plan')" name="is_free" />

      <SubmitButton :disabled="form.processing" />
    </form>
  </Modal>
</template>

<script>
import {
  Input,
  TextArea,
  CheckBox,
  SubmitButton,
} from "../../../../components/Form/Index.js";

export default {
  components: {
    Input,
    TextArea,
    CheckBox,
    SubmitButton,
  },
  props: ["data", "existing_record"],
  data() {
    return {
      form: this.$inertia.form(this.prepareForm()),
    };
  },
  methods: {
    prepareForm() {
      let inputs = {
        stripe_id: null,
        title: null,
        description: null,
        number_of_characters_allowed_per_month: null,
        price: null,
        is_free: false,
      };
      if (this.existing_record) {
        inputs = { ...inputs, ...this.existing_record };
      }
      return inputs;
    },
  },
};
</script>
