<template>
  <AppHead :title="title + ' - ' + __('Services & Pricing')" />
  <div class="container page-container">

    <PageTitle :title="__('Services & Pricing')"/>
   
    <div class="row">
      <div class="col-md-3">
      

        <div class="list-group">
          <Link
            :class="isRouteGroup('admin.authorLevels')"
            :href="route('admin.authorLevels.index')"
            class="list-group-item list-group-item-action"
            aria-current="true"
          >
            {{ __("Author Levels") }}
          </Link>
          <Link
            :class="isRouteGroup('admin.urgencies')"
            :href="route('admin.urgencies.index')"
            class="list-group-item list-group-item-action"
            >{{ __("Urgencies") }}</Link
          >
          <Link
            :class="isRouteGroup('admin.services')"
            :href="route('admin.services.index')"
            class="list-group-item list-group-item-action"
            >{{ __("Services") }}</Link
          >
          <Link
            :class="isRouteGroup('admin.subjects')"
            :href="route('admin.subjects.index')"
            class="list-group-item list-group-item-action"
            >{{ __("Subjects") }}</Link
          >
          <Link
            :class="isRouteGroup('admin.additionalServices')"
            :href="route('admin.additionalServices.index')"
            class="list-group-item list-group-item-action"
            >{{ __("Additional Services") }}</Link
          >
        </div>
      </div>
      <div class="offset-md-1 col-md-8">
        <slot />
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["title"],
  methods: {
    isRouteGroup(r) {
      return route().current(r + ".*") ? "active" : "";
    },
  },
};
</script>
