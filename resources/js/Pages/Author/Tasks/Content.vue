<template>
  <TaskShowLayout :task="task" activeTab="content">
    <div class="border p-4">
      <p class="fw-bold text-success">
        {{ __('Paste your content here to show the work progress and receive feedback from the client')  }}
      </p>
      <form @submit.prevent="form.patch(route('author.tasks.content.update', task.uuid))">
        <div class="row">
          <div class="col-md-8 border-end">
            <Input
              v-model="form.title"
              :label="__('Title')"
              :required="true"
              name="title"
            />
            <RichEditor v-model="form.content" :label="__('Content')" />
          </div>
          <div class="col-md-4">
            <div class="d-grid gap-2 mt-4">
              <button :disabled="form.processing" type="submit" class="btn btn-success">
                <i class="fa-solid fa-floppy-disk"></i> {{ __("Save") }}
              </button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </TaskShowLayout>
</template>

<script>
import TaskShowLayout from "./Partials/TaskShowLayout.vue";
import RichEditor from "../../../components/RichEditor.vue";
import { Input, SubmitButton } from "../../../components/Form/Index.js";

export default {
  props: ["task", "data"],
  components: {
    TaskShowLayout,
    RichEditor,
    Input,
    SubmitButton,
  },
  data() {
    return {
      form: this.$inertia.form(this.prepareForm()),
    };
  },
  methods: {
    prepareForm() {
      let inputs = {
        title: null,
        content: null,
      };

      if (this.task.content) {
        inputs = { ...inputs, ...this.task.content };
      }
      return inputs;
    },
  },
};
</script>
