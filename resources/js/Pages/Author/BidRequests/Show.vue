<template>
  <AppHead :title="data.title" />
  <div class="container page-container mt-3">
    <div class="row mt-4 mb-4">
      <div class="col-md-6 mb-4 mb-md-0">
        <h4>{{ data.title }}</h4>
        <InlineTags :tags="[bid_request.status]"/>
      </div>

      <div class="col-md-6 text-md-end">
        <Link
          :href="route('author.bidRequests.index')"
          class="btn btn-sm btn-light me-2"
        >
          <i class="fa-solid fa-left-long"></i> {{ __("back to") }}
          {{ __("Bid Requests") }}
        </Link>
        <Link
          v-if="data.allow_bidding"
          :href="route('author.bidRequests.bids.create', bid_request.uuid)"
          class="btn btn-sm btn-success"
        >
          <i class="fa-regular fa-rectangle-list"></i>
          {{ __("Submit Bid for this Job") }}
        </Link>

        <button
          v-if="data.allow_withdraw_bid"
          class="btn btn-sm btn-danger"
          type="button"
          @click="withdrawBid"
        >
          <i class="fa-regular fa-rectangle-list"></i>
          {{ __("Withdraw Bid") }}
        </button>
      </div>
    </div>

    <div class="row mb-4">
      <div class="col-md-12">
        <div class="text-md-end fs-7 mb-4 mb-md-0">
          <span class="text-primary"
            >{{ __("Budget") }} : {{ formatMoney(bid_request.budget) }}</span
          >
          <span v-if="data.bid" class="text-success"
            >, {{ __("Bidding Amount") }} :
            {{ formatMoney(data.bid.total) }}</span
          >
        </div>
      </div>
    </div>

    <TaskBrief :briefs="data.briefs" :task="task" :commonBriefs="commonBriefs"/>
  </div>
</template>

<script>

import TaskBrief from "../../../Shared/TaskBrief.vue";
export default {
  props: ["bid_request", "data", "task"],
  components: {
    TaskBrief,
  },
  data() {
    return {
      commonBriefs: [
        {
          label: this.__("Created"),
          value: this.localDateTime(this.bid_request.created_at),
        },        
      ],
    };
  },
  methods: {
    withdrawBid() {
      let scope = this;
      this.confirmDialog(this.__("Yes, Withdraw my bid"), function () {
        scope.$inertia.delete(
          route("author.bids.destroy", scope.data.bid.uuid),
          {
            preserveState: false,
          }
        );
      });
    },
  },
};
</script>