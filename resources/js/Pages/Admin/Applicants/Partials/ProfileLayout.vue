<template>
  <section class="d-flex align-items-end navbar-background">
    <div class="container pt-4 pt-lg-0">
      <div class="row justify-content-end">
        <div class="col-lg-9">
          <div class="d-flex justify-content-between mb-4 mt-4">
            <div>
              <span class="h2 mb-0 text-white d-block">{{
                author.full_name
              }}</span>
            </div>
            <div>
              <span class="badge bg-secondary me-2">{{
                __("Author")
              }}</span>
              <span v-if="author.inactive" class="badge bg-danger">{{
                __("Inactive")
              }}</span>
            </div>
          </div>

          <!-- Account navigation -->
          <div class="d-flex">
            <div class="btn-group flex-wrap" role="group">
              <Link
                class="btn btn-light"
                :href="route('admin.authors.show', author.uuid)"
              >
                <i class="far fa-address-card"></i> {{ __("Profile") }}
              </Link>

              <Link
                :href="route('admin.authors.edit', author.uuid)"
                class="btn btn-light"
              >
                <i class="fa-solid fa-user"></i>
                {{ __("Edit Profile") }}
              </Link>

              <DialogLink
                :href="route('admin.authors.avatar', author.uuid)"
                class="btn btn-light"
              >
                <i class="fa-solid fa-image"></i>
                {{ __("Change Avatar") }}
              </DialogLink>

              <DialogLink
                :href="route('admin.authors.password', author.uuid)"
                class="btn btn-light"
              >
                <i class="fa-solid fa-key"></i>
                {{ __("Change Password") }}
              </DialogLink>

              <div class="btn-group ml-auto" role="group">
                <button
                  id="btnGroupDrop1"
                  type="button"
                  class="btn btn-light dropdown-toggle"
                  data-bs-toggle="dropdown"
                  aria-expanded="false"
                >
                  <i class="fas fa-ellipsis-v"></i> {{ __("Activities") }}
                </button>
                <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                  <li>
                    <DialogLink
                      class="dropdown-item"
                      :href="route('admin.authors.tasks', author.uuid)"
                      >{{ __("Tasks") }}</DialogLink
                    >
                  </li>
                  <li>
                    <DialogLink
                      class="dropdown-item"
                      :href="route('admin.authors.bills', author.uuid)"
                      >{{ __("Bills") }}</DialogLink
                    >
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="pt-md-5 pt-lg-0">
    <div class="container">
      <div class="row">
        <div class="col-lg-3">
          <div class="sticky-top profile-photo-holder">
            <div class="card border-0">
              <div class="card-profile-cover">
                <img
                  :alt="author.full_name"
                  :src="author.small_avatar"
                  class="card-img-top author-avatar"
                />
              </div>
              <div class="mx-auto">
                <img
                  alt="Image placeholder"
                  :src="author.small_avatar"
                  class="
                    card-profile-image
                    avatar
                    rounded-circle
                    shadow
                    hover-shadow-lg
                    author-avatar
                  "
                />
              </div>
              <div class="card-body p-3 pt-0 text-center">
                <h5 class="mb-0">{{ author.full_name }}</h5>                

                <div
                  class="
                    actions
                    d-flex
                    justify-content-center
                    mt-3
                    pt-3
                    px-5
                    delimiter-top
                  "
                >
                  <Link @click="destroy" href="#" class="text-danger">
                    <i class="far fa-trash-alt"></i> {{ __("Delete") }}
                  </Link>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-9 mt-2 page-container"><slot /></div>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  props: ["author"],
  methods: {
    destroy() {
      this.deleteConfirmDialog(
        this,
        route("admin.authors.destroy", this.author.uuid)
      );
    },
  },
};
</script>