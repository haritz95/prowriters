<template>
  <AppHead :title="data.title" />
  <div class="container page-container">
    <div class="row">
      <div class="col-md-12">
        <div class="d-flex justify-content-between flex-column d-sm-flex flex-sm-row">
          <Link class="btn btn-sm btn-light" :href="route('admin.messageThreads.index')">
            <i class="fa-solid fa-arrow-left-long"></i>
            {{ __("Back to Message Threads") }}
          </Link>

          <div id="right-side">
            <Link
              class="btn btn-light btn-sm me-2"
              :href="route('admin.messageThreads.edit', data.messageThread.uuid)"
            >
              <i class="fa-regular fa-pen-to-square"></i> {{ __("Edit") }}
            </Link>

            <button @click="destroy" class="btn btn-danger btn-sm me-2" href="#">
              <i class="fa-solid fa-trash-can"></i> {{ __("Delete") }}
            </button>

            <DialogLink
              :href="
                route('admin.messageThreads.messages.create', data.messageThread.uuid)
              "
              class="btn btn-sm btn-primary"
            >
              {{ __("Send Reply") }}</DialogLink
            >
          </div>
        </div>
      </div>
    </div>

    <div class="mb-4 mt-4">
      <hr />
      <small class="text-muted">{{ __("Subject") }} : </small>
      <h3 class="text-muted">{{ data.messageThread.subject }}</h3>
    </div>

    <div class="row">
      <div class="col-md-3">
        <div>{{ __("Created at") }}:</div>
        <small class="text-muted">{{ localDate(data.messageThread.created_at) }}</small>

        <div class="mt-4">{{ __("Created by") }}:</div>
        <small class="text-muted">
          <Link :href="data.urls.sender_profile_url">
            {{ data.messageThread.sender.full_name }}</Link
          ></small
        >

        <div class="mt-4">{{ __("Recipient") }}:</div>
        <small class="text-muted"
          ><Link :href="data.urls.recipient_profile_url">{{
            data.messageThread.recipient.full_name
          }}</Link></small
        >

        <div class="mt-4">{{ __("Participants") }}:</div>

        <div
          class="text-muted"
          v-for="(row, index) in data.messageThread.participants"
          :key="index"
        >
          <Link
            :href="data.urls.recipient_profile_url"
            v-if="
              ![data.messageThread.recipient.id, data.messageThread.sender.id].includes(
                row.user.id
              )
            "
            >{{ row.user.full_name }}</Link
          >
        </div>
      </div>
      <div class="col-md-9">
        <DiscussionBoard :messages="messages" :data="data" :show_name="true" />
      </div>
    </div>
  </div>
</template>

<script>
import DiscussionBoard from "../../../components/DiscussionBoard.vue";

export default {
  props: ["data", "messages"],
  components: {
    DiscussionBoard,
  },
  methods: {
    destroy() {
      this.deleteConfirmDialog(
        this,
        route("admin.messageThreads.destroy", this.data.messageThread.uuid)
      );
    },
  },
};
</script>
