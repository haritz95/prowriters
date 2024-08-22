<template>
  <Modal :title="data.title" size="small">
    <form @submit.prevent="form.post(route('admin.tasks.update.assignee', task.uuid))">
      <SelectUser
        :options="data.assignees"
        v-model="form.author_id"
        :label="__('Author')"        
        :clearable="true"
        name="author_id"
      />

      <DatePicker
        v-model="form.dead_line_for_author"
        :label="__('Due date for the Author')"
        name="dead_line_for_author"
      />

      <Input
        v-model="form.author_payment_amount"
        :label="__('Payment Amount')"
        name="author_payment_amount"
      />

      <SubmitButton :disabled="form.processing" />
    </form>
  </Modal>
</template>

<script>
import {
  Input,
  SelectUser,
  SubmitButton,
  DatePicker,
} from "../../../components/Form/Index.js";

export default {
  props: ["data", "task"],
  components: {
    Input,
    SelectUser,
    SubmitButton,
    DatePicker,
  },
  data() {
    return {
      form: this.$inertia.form({
        author_id: this.task.author_id,
        author_payment_amount: this.task.author_payment_amount
          ? parseFloat(this.task.author_payment_amount).toFixed(2)
          : 0,
        dead_line_for_author: this.task.dead_line_for_author,
      }),
    };
  },
};
</script>
