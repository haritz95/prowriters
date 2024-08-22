<template>
  <div class="comment-box pb-0" v-for="(message, index) in messages.data" :key="index">
    <div class="row">
      <div class="col-md-3 text-center">
        <img
          class="avatar"
          :src="message.user.small_avatar"
          :alt="message.user.code"
          loading="lazy"
        />
        <div class="mt-2"></div>
        <div v-if="show_name">{{ message.user.full_name }}</div>
        <div>{{ message.user.code }}</div>
        <div class="text-muted">
          <span v-if="message.user.type == data.user_types.admin">
            {{ __("Admin") }}
          </span>
          <span v-else-if="message.user.type == data.user_types.author">
            {{ __("Writer") }}
          </span>
          <span v-else>
            {{ __("Customer") }}
          </span>
        </div>
      </div>
      <div class="col-md-9 comment-body-parent">
        <div class="nl2br" v-html="message.body"></div>
        <div v-if="message.attachments.length > 0">
          <hr />
          <div class="mb-2 mt-2">{{ __("Attachments") }}</div>
          <AttachmentList :attachments="message.attachments" />
        </div>
      </div>
    </div>
    <br /><br />
    <div class="comment-box-footer">
      <div class="d-flex justify-content-between">
        <div>{{ __("Posted") }} : {{ localDateTime(message.created_at) }}</div>
        <div
          class="text-end"
          v-if="message.uuid && $page.props.auth_user.type == data.user_types.admin"
        >
          <button
            @click="
              deleteConfirmDialog(this, route(data.urls.route_name_destroy, message.uuid))
            "
            type="button"
            class="btn btn-sm btn-light"
          >
            {{ __("Delete") }}
          </button>
        </div>
      </div>
    </div>
  </div>

  <div v-if="messages.total == 0" class="text-center text-muted">
    {{ __("No record found") }}
  </div>
  <Pagination v-if="messages.data.length > 0" :links="messages.links" />
</template>

<script>
import Pagination from "./Pagination.vue";
import AttachmentList from "./AttachmentList.vue";

export default {
  props: ["messages", "data", "show_name"],
  components: {
    Pagination,
    AttachmentList,
  },
};
</script>
