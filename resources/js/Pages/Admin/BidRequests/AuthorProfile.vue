<template>
  <Modal :title="data.title">
    <div class="d-flex">
      <div class="flex-shrink-0">
        <img
          class="avatar rounded-circle me-2"
          :src="data.author.small_avatar"
          alt="..."
          loading="lazy"
        />
      </div>
      <div class="flex-grow-1 ms-3">
        {{ __("ID") }} : <strong>{{ data.author.code }}</strong>
        <div>
          <Star :number="data.author.author_ratings_avg_number" />          
        </div>
        <p class="nl2br" v-html="data.author.author_profile.bio"></p>
      </div>
    </div>
    <hr />

    <div class="row fs-8 text-center">
      <div class="col-md-4 border-end">
        <div>{{ __("Tasks Completed") }}</div>
        <div><strong>{{ data.author.completed_tasks_count }}</strong></div>
      </div>
      <div class="col-md-4 border-end">
        <div>{{ __("Education Level") }}</div>
        <div><strong>{{ data.author.author_profile.author_level.name }}</strong></div>
      </div>
      <div class="col-md-4">
        <div>{{ __("Writer Level") }}</div>
        <div><strong>{{ data.author.author_profile.education_level.name }}</strong></div>
      </div>
    </div>
    <hr/>
    <h5>{{ __('Latest reviews')  }} (5)</h5>
    <hr/>
    <div class="fs-8" v-if="data.author.author_ratings.length > 0">
        <div v-for="(rating, index) in data.author.author_ratings" :key="index">
            <Star :number="rating.number" :comment="rating.comment" /> 
            <hr/>
        </div>
    </div>
    <small v-else>{{ __('No reviews yet') }}</small>
  </Modal>
</template>


<script>
import Star from "../../../components/Star.vue";
export default {
  props: ["data"],
  components: {
    Star,
  },
  data() {
    return {
      tableOptions: {
        titles: [
          {
            name: this.__("Date"),
            className: "col-md-2",
          },
          {
            name: this.__("From"),
            className: "col-md-3",
          },

          {
            name: this.__("Bid Amount"),
            className: "col-md-2 text-end",
          },
          {
            name: this.__("Action"),
            className: "col-md-2 text-end",
          },
        ],
      },
    };
  },
  methods: {
    accept(bidUUID) {
      let scope = this;
      this.confirmDialog(this.__("Accept the offer"), function () {
        scope.$inertia.post(
          route("customer.bidRequests.accept", [
            scope.data.bid_request.uuid,
            bidUUID,
          ])
        );
      });
    },
  },
};
</script>