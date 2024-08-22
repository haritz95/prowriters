<template>
  <AppHead :title="data.title" />
  <div class="container page-container">
    <PageTitle :title="data.title" />

    <div class="row">
      <div class="col-md-4 text-center mb-4" v-for="(row, key) in data.services" :key="key">
        <div class="border p-2 h-100 d-flex flex-column justify-content-between">
          <div>
            <div>
              <img
                :src="asset(row.image)"
                alt="twbs"
                class="img-fluid rounded-circle flex-shrink-0"
              />
            </div>
            <h2 class="title h4">{{ row.name }}</h2>
            <p class="description nl2br" v-html="row.description"></p>
          </div>
          <div class="d-grid gap-2">
            <Link
              :href="route(data.route_name, row.slug)"
              class="align-self-end btn btn-primary"
              >{{ __("Select") }} <i class="fa-solid fa-angle-right"></i
            ></Link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["data"],
};
</script>
