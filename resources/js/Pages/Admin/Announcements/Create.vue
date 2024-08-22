<template>
  <AppHead :title="data.title" />
  <div class="container page-container">
    <PageTitle :title="data.title" :sub_title="__('For authors')">
      <Link
        v-if="existing_record"
        class="btn btn-sm btn-light"
        :href="route('admin.announcements.show', existing_record.uuid)"
      >
        <i class="fa-solid fa-arrow-left-long"></i> {{ __("Back to the Announcement") }}
      </Link>
      <Link
        v-else
        class="btn btn-sm btn-light"
        :href="route('admin.announcements.index')"
      >
        <i class="fa-solid fa-arrow-left-long"></i> {{ __("Back to Announcements") }}
      </Link>
    </PageTitle>

    <form
      @submit.prevent="
        existing_record
          ? form.patch(route('admin.announcements.update', existing_record.uuid))
          : form.post(route('admin.announcements.store'), formConfig)
      "
    >
      <Input v-model="form.title" :label="__('Title')" name="title" :required="true" />

      <RichEditor
        v-model="form.content"
        :label="__('Announcement Content')"
        name="content"
        :required="true"
      />

      <CheckBox
        v-model="form.inactive"
        name="inactive"
        :label="__('Inactive')"
        :tooltip="
          __('Inactive will make the Announcement hidden from Author Dashboard')
        "
      />

      <SubmitButton :disabled="form.processing" />
    </form>
  </div>
</template>

<script>
import { Input, SubmitButton, CheckBox } from "../../../components/Form/Index.js";
import RichEditor from "../../../components/RichEditor.vue";

export default {
  components: {
    Input,
    SubmitButton,
    RichEditor,
    CheckBox,
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
        title: null,
        content: null,
        inactive: null,
      };

      if (this.existing_record) {
        inputs = { ...inputs, ...this.existing_record };
      }
      return inputs;
    },
  },
};
</script>
