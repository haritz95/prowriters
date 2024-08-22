<template>
  <AppHead :title="data.title" />
  <div class="container page-container">
    <div class="row">
      <div class="col-md-12">
        <div class="d-flex justify-content-between flex-column d-sm-flex flex-sm-row">
          <div>
            <Link
              class="btn btn-sm btn-light"
              :href="route('author.messageThreads.index')"
            >
              <i class="fa-solid fa-arrow-left-long"></i>
              {{ __("Back to Message Threads") }}
            </Link>
          </div>

          <div>
            <DialogLink
              :href="
                route('author.messageThreads.messages.create', data.messageThread.uuid)
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
        <small class="text-muted">{{ data.messageThread.sender.full_name }}</small>

        <div class="mt-4">{{ __("Recipient") }}:</div>
        <small class="text-muted">{{ data.messageThread.recipient.full_name }}</small>
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
};
</script>
