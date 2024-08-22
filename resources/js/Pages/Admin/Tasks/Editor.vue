<template>
  <Modal :title="data.title" size="small">
    <form @submit.prevent="form.post(route('admin.tasks.update.editor', task.uuid))">
      <SelectUser
        :options="data.assignees"
        v-model="form.editor_id"
        :label="__('Editor for the task')"        
        :clearable="true"
        name="editor_id"
      />

      <SubmitButton :disabled="form.processing" />
    </form>
  </Modal>
</template>

<script>
import { SelectUser, SubmitButton } from "../../../components/Form/Index.js";

export default {
  props: ["data", "task"],
  components: {
    SelectUser,
    SubmitButton,
  },
  data() {
    return {
      form: this.$inertia.form({
        editor_id: this.task.editor_id,
      }),
    };
  },
};
</script>
