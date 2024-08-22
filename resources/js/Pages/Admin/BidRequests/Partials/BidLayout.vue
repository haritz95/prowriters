<template>
  <AppHead :title="bid_request.number" />
  <section class="" data-offset-top="#header-main">
    <div class="container pt-4 pt-lg-0">
      <div class="row mt-4">
        <div class="col-md-4">
          <h4>{{ __("Bid Request") }} #{{ bid_request.number }}</h4>
          <span
            class="badge"
            :class="'text-bg-' + bid_request.status.css_badge_name"
            >{{ bid_request.status.name }}</span
          >
        </div>

        <div class="col-md-8 text-end">
          <div class="d-flex justify-content-end flex-sm-row flex-column">
            <Link
              :href="route('admin.bidRequests.index')"
              class="btn btn-sm btn-light me-2 mb-2 mt-2"
            >
              <i class="fa-solid fa-left-long"></i> {{ __("back to") }}
              {{ __("Bid Requests") }}
            </Link>

            <!-- <button
              class="btn btn-sm btn-outline-secondary me-2 mb-2 mt-2"
              @click="destroyBidRequest"
            >
              <i class="fa-solid fa-ban"></i> {{ __("Mark as cancelled") }}
            </button>
            <button
              class="btn btn-sm btn-outline-secondary me-2 me-2 mb-2 mt-2"
              @click="destroyBidRequest"
            >
              <i class="fa-regular fa-circle-pause"></i>
              {{ __("Mark as on hold") }}
            </button> -->

            <button class="btn btn-sm btn-danger me-2 mb-2 mt-2" @click="destroyBidRequest">
              <i class="fa-solid fa-trash-can"></i> {{ __("Delete") }}
            </button>
           
          </div>
          <div class="mt-4 text-start text-md-end">
              {{ __("Customer") }} :
              <Link>{{ bid_request.task.customer.full_name }}</Link>
            </div>
        </div>

        <div class="col-md-12">
          <!-- Account navigation -->
          <div class="d-flex mt-4 fs-8">
            <ul class="nav nav-tabs task-navigation" id="myTab" role="tablist">
              <li class="nav-item">
                <Link
                  class="nav-link"
                  :class="{ active: isActiveTab('bids') }"
                  :href="route('admin.bidRequests.show', bid_request.uuid)"
                  >{{ __("Bids") }}</Link
                >
              </li>
              <li class="nav-item">
                <Link
                  class="nav-link"
                  :class="{ active: isActiveTab('brief') }"
                  :href="route('admin.bidRequests.brief', bid_request.uuid)"
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
    destroyBidRequest() {
      this.deleteConfirmDialog(
        this,
        route("admin.bidRequests.destroy", this.bid_request.uuid)
      );
    },
  },
};
</script>