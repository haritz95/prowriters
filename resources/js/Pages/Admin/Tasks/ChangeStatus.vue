<template>
  <Modal :title="data.title">
    <form
      @submit.prevent="form.patch(route('admin.tasks.update.status', task.uuid))"
    >
      <Select
        v-model="form.task_status_id"
        :options="data.task_statuses"
        :label="__('Status')"
        name="task_status_id"
        :searchable="true"
      />

      <SubmitButton :disabled="form.processing" />
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
        task_status_id: this.task.task_status_id,
      }),
    };
  },
};
</script>