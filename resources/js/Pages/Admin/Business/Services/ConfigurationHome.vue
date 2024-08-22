<template>
  <BusinessLayout :title="data.title">
    <div class="row">
      <div class="col-md-6">
        <h5 class="mb-0">{{ __("Configure") }} {{ data.service.name }}</h5>
      </div>
      <div class="col-md-6">
        <div class="float-end">
          <Link :href="route('admin.services.index')" class="btn btn-sm btn-light mx-1"
          ><i class="fa-solid fa-angle-left"></i> {{ __('Back to services') }}</Link
        >
        </div>
      </div>
    </div>

   
    <hr />
    <div class="text-center">
      <div class="list-group">
        <Link
          v-for="(row, key) in data.links"
          :key="key"
          :href="row.list"
          class="list-group-item list-group-item-action d-flex gap-3"
          aria-current="true"
        >
          <!-- <img
              width="40"
              height="40"
              :src="data.icons[row.id]"
              alt="twbs"
              class="rounded-circle flex-shrink-0"
            /> -->
          <!-- <i :class="row.icon" class="flex-shrink-0"></i> -->
          <div class="d-flex gap-2 w-100 justify-content-between align-items-center">
            <div>
              <h6 class="mb-0">{{ row.name }}</h6>
            </div>
            <small class="opacity-50 text-nowrap"
              ><i class="fa-solid fa-angle-right"></i
            ></small>
          </div>
        </Link>
      </div>
    </div>
  </BusinessLayout>
</template>

<script>
import BusinessLayout from "../Partials/BusinessLayout.vue";
import SearchBar from "../../../../Shared/SearchBar.vue";
import TitleBar from "../Partials/TitleBar.vue";
import DestroyButton from "../../../../components/Form/DestroyButton.vue";

import Table from "../../../../components/Table.vue";

export default {
  components: {
    BusinessLayout,
    SearchBar,
    Table,
    TitleBar,
    DestroyButton,
  },
  props: ["data"],
  data() {
    return {
      tableOptions: {
        titles: [
          {
            name: this.__("Name"),
            className: "col-md-6",
          },

          {
            name: this.__("Action"),
            className: "col-md-6 text-end",
          },
        ],
      },
    };
  },
};
</script>
