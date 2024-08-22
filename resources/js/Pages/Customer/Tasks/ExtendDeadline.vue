<template>
  <Modal :title="__('Extend Deadline')">
    <form @submit.prevent="form.post(data.urls.submit_form, formConfig)">
      <Select
        :options="data.dropdowns.deadline_options"
        v-model="form.deadline"
        :label="__('Deadline')"
        :required="true"
        name="deadline"
        :placeholder="__('Select to add')"
      />
      <SubmitButton :disabled="form.disabled" />
    </form>
  </Modal>
</template>
  
  <script>
import { Select, SubmitButton } from "../../../components/Form/Index.js";
export default {
  props: ["task", "data"],
  components: {
    Select,
    SubmitButton,
  },
  data() {
    return {
      form: this.$inertia.form({
        deadline: null,
      }),
      formConfig: {
        preserveScroll: false,
      },
    };
  },
};
</script>