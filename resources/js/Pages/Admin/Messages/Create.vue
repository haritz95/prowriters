<template>
  <AppHead :title="data.title" />
  <div class="container page-container">
    <PageTitle :title="data.title">
      <Link class="btn btn-sm btn-light" :href="route('admin.messageThreads.index')">
        <i class="fa-solid fa-arrow-left-long"></i> {{ __("Back to Messages") }}
      </Link>
    </PageTitle>
    <form @submit.prevent="form.post(route('admin.messageThreads.store'), formConfig)">
      <SearchAllUser
        :options="data.recipients"
        v-model="form.recipient_id"
        :label="__('Recipient')"
        name="recipient_id"
        :required="true"
        :multiple="false"
      />

      <SearchAllUser
        :options="data.participants"
        v-model="form.participants"
        :label="__('Participants') + ' / ' + __('CC')"
        name="participants"
        :multiple="true"
      />

      <Input
        v-model="form.subject"
        :label="__('Subject')"
        name="subject"
        :required="true"
      />

      <Editor v-model="form.body" :label="__('Message')" name="body" :required="true" />

      <Attachment
        @onChange="handleAttachment"
        :upload_attachment_url="data.config.urls.upload_attachment"
        :allowed_file_extensions="data.config.allowed_file_extensions"
        :maximum_number_of_files_to_upload="data.config.maximum_number_of_files_to_upload"
        :maximum_file_size="data.config.maximum_file_size"
        :existing_files="data.config.existing_files"
        :config="data.config"
      ></Attachment>
      <ValidationError name="files" />

      <SubmitButton :disabled="form.processing" />
    </form>
  </div>
</template>

<script>
import { SearchAllUser, Input, SubmitButton } from "../../../components/Form/Index.js";
import Editor from "../../../components/Editor.vue";
import Attachment from "../../../components/Attachment.vue";

export default {
  components: {
    SearchAllUser,
    Attachment,
    Input,
    SubmitButton,
    Editor,
  },
  props: ["data"],
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
        recipient_id: null,
        participants: null,
        subject: null,
        body: null,
        files: null,
      };
    },
    handleAttachment(files) {
      this.form.files = files;
    },
  },
};
</script>
