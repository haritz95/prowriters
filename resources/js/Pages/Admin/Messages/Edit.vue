<template>
  <AppHead :title="data.title" />
  <div class="container page-container">
    <PageTitle :title="data.title">
      <Link
        class="btn btn-sm btn-light"
        :href="route('admin.messageThreads.show', existing_record.uuid)"
      >
        <i class="fa-solid fa-arrow-left-long"></i> {{ __("Back to the Thread") }}
      </Link>
    </PageTitle>
    <form
      @submit.prevent="
        form.patch(route('admin.messageThreads.update', existing_record.uuid))
      "
    >
      <Input
        v-model="form.subject"
        :label="__('Subject')"
        name="subject"
        :required="true"
      />

      <SearchAllUser
        :options="data.participants"
        v-model="form.participants"
        :label="__('Participants') + ' / ' + __('CC')"
        name="participants"
        :multiple="true"
      />

      <SubmitButton :disabled="form.processing" />
    </form>
  </div>
</template>

<script>
import { SearchAllUser, Input, SubmitButton } from "../../../components/Form/Index.js";

export default {
  components: {
    SearchAllUser,
    Input,
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
      return {
        participants: this.existing_record.participants,
        subject: this.existing_record.subject,
      };
    },
  },
};
</script>
