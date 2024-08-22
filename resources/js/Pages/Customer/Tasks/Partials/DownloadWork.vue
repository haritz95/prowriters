<template>
  <div class="card mb-4" v-if="data.allow.download_work">
    <div class="card-header">
      <div>{{ __("Download Work") }}</div>
      <small class="text-muted"
        >{{ __("Posted") }} : {{ localDateTime(comment.created_at) }}</small
      >
    </div>
    <div class="card-body">
      <div class="fs-8">
        <div class="text-break nl2br" v-html="comment.message"></div>
        <div class="mt-3" v-if="comment.attachments.length > 0">
          <hr />
          <div class="mb-4">{{ __("Attachments") }}</div>
          <AttachmentList :attachments="comment.attachments" />
        </div>
      </div>

      <div class="card-footer" v-if="task.allowed_to_accept_or_revise">
        <Link
          :href="route('customer.tasks.content.accept', task.uuid)"
          method="post"
          as="button"
          type="button"
          class="btn btn-sm btn-primary me-2"
        >
          <i class="fas fa-check-circle"></i>
          {{ __("Accept") }}
        </Link>

        <Link
          :href="route('customer.tasks.revisions.create', task.uuid)"
          class="btn btn-sm btn-outline-danger me-2"
        >
          <i class="fa-solid fa-pen-to-square"></i>
          {{ __("Request for revision") }}
        </Link>
      </div>

      <div class="card-footer" v-if="task.allow_customer_to_submit_rating">
        <Link
          class="btn btn-sm btn-dark"
          :href="route('customer.tasks.ratings.create', task.uuid)"
        >
          <i class="fa-regular fa-star"></i> {{ __("Submit your rating") }}
        </Link>
      </div>
      <div class="card-footer" v-if="task.show_rating">
        <small class="text-muted mb-2">{{ __("Your Rating") }} :</small>
        <Star :number="task.rating.number" :comment="task.rating.comment" />
      </div>
    </div>
  </div>
  <div class="text-center border p-4 text-danger mb-4" v-else>
    {{ __("Download link of your content will appear here once it is ready") }}
  </div>
</template>
<script>
import Star from "../../../../components/Star.vue";
import AttachmentList from "../../../../components/AttachmentList.vue";
export default {
  props: ["task", "data"],
  components: {
    Star,
    AttachmentList,
  },
  data() {
    return {
      comment: (this.task.submitted_works) ? this.task.submitted_works[0] : [],
    };
  },
};
</script>