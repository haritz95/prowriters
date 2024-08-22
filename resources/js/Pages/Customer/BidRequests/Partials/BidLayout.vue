<template>
  <AppHead :title="bid_request.number" />
  <section class="" data-offset-top="#header-main">
    <div class="container pt-4 pt-lg-0">
      <div class="row mt-3">
        <div class="col-lg-12">
          <div class="text-end text-white"></div>
          <div class="d-flex justify-content-between mt-4">
            <div>
              <h4>{{ __("Bid Request") }} #{{ bid_request.number }}</h4>
              <span class="badge" :class="'text-bg-' + bid_request.status.css_badge_name">{{
                     bid_request.status.name
                  }}</span>
            </div>

            <div>
              <Link :href="route('customer.bidRequests.index')" class="btn btn-sm btn-light me-2">
                <i class="fa-solid fa-left-long"></i> {{ __("back to") }} {{ __("Bid Requests") }}
              </Link>
              <button class="btn btn-sm btn-danger" @click="destroyBidRequest">
                <i class="fa-solid fa-trash-can"></i> {{ __("Delete") }}
              </button>
            </div>
          </div>

          <!-- Account navigation -->
          <div class="d-flex mt-4 fs-8">
            <ul class="nav nav-tabs task-navigation" id="myTab" role="tablist">
              <li class="nav-item">
                <Link
                  class="nav-link"
                  :class="{ active: isActiveTab('bids') }"
                  :href="route('customer.bidRequests.show', bid_request.uuid)"
                  >{{ __("Bids") }}</Link
                >
              </li>
              <li class="nav-item">
                <Link
                  class="nav-link"
                  :class="{ active: isActiveTab('brief') }"
                  :href="route('customer.bidRequests.brief', bid_request.uuid)"
                  aria-selected="true"
                  >{{ __("Task Information") }}</Link
                >
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>

  <div class="container page-container mt-3">
    <div class="row">
      <div class="col-md-12">
        <slot />
      </div>
    </div>
  </div>
</template>
  
  <script>
export default {
  props: ["bid_request", "activeTab"],
  methods: {
    isActiveTab(tab) {
      return this.activeTab == tab ? true : false;
    },
    destroyBidRequest(){
      this.deleteConfirmDialog(this, route('customer.bidRequests.destroy', this.bid_request.uuid));
    }
  },
};
</script>