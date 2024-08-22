<template>
  <Modal :title="data.title" size="full_screen">
    <form @submit.prevent="form.patch(route('admin.tasks.content.update', task.uuid))">
      <div class="row">
        <div class="col-md-9 border-end">
          <Input
            v-model="form.title"
            :label="__('Title')"
            :required="true"
            name="title"
          />
          <RichEditor v-model="form.content" :label="__('Content')" />
        </div>
        <div class="col-md-3">
            <p class="fw-bold text-danger">
            {{ __('Paste your content here to show the work progress and receive feedback from the client')  }}
          </p>
          <div class="d-grid gap-2 mt-4">
            <button :disabled="form.processing" type="submit" class="btn btn-success">
              <i class="fa-solid fa-floppy-disk"></i> {{ __("Save") }}
            </button>
          </div>
          
        </div>
      </div>
    </form>
  </Modal>
</template>

<script>
import TaskShowLayout from "../Partials/TaskShowLayout.vue";

import RichEditor from "../../../../components/RichEditor.vue";
import { Input, SubmitButton } from "../../../../components/Form/Index.js";

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
