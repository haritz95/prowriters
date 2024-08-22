<template>
  <Modal :title="data.title" size="regular">
    <form
      @submit.prevent="
        existing_record
          ? form.patch(data.urls.submit_form)
          : form.post(data.urls.submit_form, formConfig)
      "
    >
      <Input v-model="form.name" name="name" :label="__('Name')" :required="true" />

      <TextArea
        v-model="form.description"
        name="description"
        :label="__('Description')"
        :required="true"
      />

      <Select
        :searchable="true"
        :options="data.dropdowns.urgencies"
        v-model="form.urgency_id"
        :label="__('Turnaround Time')"
        :required="true"
        name="urgency_id"
      />

      <div class="row">
        <div class="col-md-6"></div>
      </div>

      <Select
        :searchable="true"
        :options="data.dropdowns.author_levels"
        v-model="form.author_level_id"
        :label="__('Author Level')"
        :required="true"
        name="author_level_id"
      />

      <Input
        v-model="form.price"
        name="price"
        :label="__('Price')"
        :required="true"
        @keypress="onlyNumber($event, form.price)"
      />

      <Input
        v-model="form.author_payment_amount"
        name="author_payment_amount"
        :label="__('Author Payment Amount')"
        :required="true"
        @keypress="onlyNumber($event, form.author_payment_amount)"
      />

      <Editor
        v-model="form.deliverables"
        name="deliverables"
        :label="__('Deliverables')"
        :required="true"
      />

      <SubmitButton :disabled="form.processing" />
    </form>
  </Modal>
</template>

<script>
import {
  Input,
  Select,
  TextArea,
  SubmitButton,
} from "../../../../components/Form/Index.js";
import Editor from "../../../../components/Editor.vue";

export default {
  components: {
    Input,
    TextArea,
    Select,
    Editor,
    SubmitButton,
  },
  props: ["data", "existing_record"],
  data() {
    return {
      form: this.$inertia.form(this.prepareForm()),
      formConfig: {
        onSuccess: () => this.form.reset(),
      },
    };
  },
  methods: {
    prepareForm() {
      let inputs = {
        service_id: null,
        name: null,
        price: 0,
        author_level_id: null,
        author_payment_amount: null,
        urgency_id: null,
        description: null,
        deliverables: null,
        url_query_parameters: this.data.url_query_parameters,
      };
      if (this.existing_record) {
        inputs = { ...inputs, ...this.existing_record };
      }
      return inputs;
    },
  },
};
</script>
