<template>
  <Modal :title="data.title">
    <form
      @submit.prevent="
        existing_record
          ? form.patch(
              route('admin.manage.content.testimonials.update', [
                content_language,
                existing_record.id,
              ])
            )
          : form.post(
              route('admin.manage.content.testimonials.store', content_language)
            )
      "
    >
      <Input
        v-model="form.name"
        name="name"
        :label="__('Customer Name')"
        :required="true"
      />

      <Input
        v-model="form.position"
        name="position"
        :label="__('Customer Position')"
        :required="true"
      />

      <FileChooser
        v-model="form.avatar"
        :label="__('Customer Avatar')"
        name="avatar"
        :required="true"
      />

      <Select
        :options="data.dropdowns.ratings"
        v-model="form.rating"
        :label="__('Rating')"
        :required="true"
        name="rating"
      />      

      <TextArea
        v-model="form.comment"
        name="comment"
        :label="__('Comment')"
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
  FileChooser,
} from "../../../../components/Form/Index.js";

export default {
  components: {
    Input,
    Select,
    TextArea,
    SubmitButton,
    FileChooser,
  },
  props: ["data", "content_language", "existing_record"],
  data() {
    return {
      form: this.$inertia.form(this.prepareForm()),
    };
  },
  methods: {
    prepareForm() {
      let inputs = {
        name: null,
        position: null,
        rating: 5,
        comment: null,
      };
      if (this.existing_record) {
        inputs = { ...inputs, ...this.existing_record };
      }
      return inputs;
    },
  },
};
</script>