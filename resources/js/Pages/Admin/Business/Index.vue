<template>
  <AppHead :title="data.title" />
  <div class="container page-container">
    <PageTitle :title="data.title" />

    <div class="text-center">
      <div class="list-group list-group-horizontal-md">
        <Link
          v-for="(row, key) in data.links"
          :key="key"
          :href="row.link"
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
          <i :class="row.icon" class="flex-shrink-0"></i>
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
  </div>
</template>

<script>
export default {
  props: ["data"],
};
</script>
