<template>
  <div class="flex-shrink-0 w-100">
    <ul class="list-unstyled ps-0">
      <li class="mb-1" v-for="(item, index) in items" :key="index">
        <button
          class="btn btn-toggle align-items-center rounded collapsed fs-8 w-100"
          data-bs-toggle="collapse"
          :data-bs-target="'#selector_' + index"
          aria-expanded="true"
        >
          {{ item.name }}
        </button>
        <div
          class="collapse"
          :class="isRouteGroup(item.route_group)"
          :id="'selector_' + index"
        >
          <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
            <li>
              <Link :href="item.create" class="link-dark rounded">{{
                __("Create New")
              }}</Link>
            </li>
            <li>
              <Link :href="item.list" class="link-dark rounded">{{
                __("List")
              }}</Link>
            </li>
          </ul>
        </div>
      </li>

      <li class="border-top my-3"></li>
    </ul>

    <!-- <div class="d-grid gap-2">
      <Link :href="route('admin.services.index')" class="btn btn-light">{{
        __("Configure Services")
      }}</Link>
    </div> -->
  </div>
</template>

<script>
export default {
  data() {
    return {
      items: [
        // {
        //   name: this.__("Author Levels"),
        //   create: this.route("admin.authorLevels.create"),
        //   list: this.route("admin.authorLevels.index"),
        //   route_group: "admin.authorLevels",
        // },
        // {
        //   name: this.__("Urgencies"),
        //   create: this.route("admin.urgencies.create"),
        //   list: this.route("admin.urgencies.index"),
        //   route_group: "admin.urgencies",
        // },
        {
          name: this.__("Assignments / Packages"),
          create: this.route("admin.assignments.create"),
          list: this.route("admin.assignments.index"),
          route_group: "admin.assignments",
        },
        
        {
          name: this.__("Subjects"),
          create: this.route("admin.subjects.create"),
          list: this.route("admin.subjects.index"),
          route_group: "admin.subjects",
        },
        {
          name: this.__("Academic Levels"),
          create: this.route("admin.academicLevels.create"),
          list: this.route("admin.academicLevels.index"),
          route_group: "admin.academicLevels",
        },
        {
          name: this.__("Paper Formats"),
          create: this.route("admin.paperFormats.create"),
          list: this.route("admin.paperFormats.index"),
          route_group: "admin.paperFormats",
        },
        {
          name: this.__("Languages"),
          create: this.route("admin.languages.create"),
          list: this.route("admin.languages.index"),
          route_group: "admin.languages",
        },

        {
          name: this.__("Grammatical People"),
          create: this.route("admin.grammaticalPeople.create"),
          list: this.route("admin.grammaticalPeople.index"),
          route_group: "admin.grammaticalPeople",
        },
        {
          name: this.__("Additional Services"),
          create: this.route("admin.additionalServices.create"),
          list: this.route("admin.additionalServices.index"),
          route_group: "admin.additionalServices",
        },
        // {
        //   name: this.__("Customer Service Levels"),
        //   create: this.route("admin.serviceLevels.create"),
        //   list: this.route("admin.serviceLevels.index"),
        //   route_group: "admin.serviceLevels",
        // },
      ],
    };
  },
  methods: {
    isRouteGroup(r) {
      return route().current(r + ".*") ? "show" : "";
    },
  },
};
</script>