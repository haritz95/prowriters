<template>
  <section class="d-flex align-items-end navbar-background">
    <div class="container pt-4 pt-lg-0">
      <div class="row justify-content-end">
        <div class="col-lg-9">
          <div class="d-flex justify-content-between mb-4 mt-4">
            <span class="h2 mb-0 text-white d-block">{{ user.full_name }}</span>
            <div>
              <span class="badge bg-secondary me-2">{{ __("Admin") }}</span>
              <span v-if="user.inactive" class="badge bg-danger">{{
                __("Inactive")
              }}</span>
            </div>
          </div>

          <!-- Account navigation -->
          <div class="d-flex">
            <div class="btn-group" role="group">
              <DialogLink
                :href="route('admin.users.edit', user.uuid)"
                class="btn btn-light"
              >
                <i class="fa-solid fa-user"></i>
                {{ __("Edit Profile") }}
              </DialogLink>
             

              <DialogLink
                :href="route('admin.users.avatar', user.uuid)"
                class="btn btn-light"
              >
                <i class="fa-solid fa-image"></i>
                {{ __("Change Avatar") }}
              </DialogLink>

              <DialogLink
                :href="route('admin.users.password', user.uuid)"
                class="btn btn-light"
              >
                <i class="fa-solid fa-key"></i>
                {{ __("Change Password") }}
              </DialogLink>

              <DialogLink
                :href="route('admin.users.permission', user.uuid)"
                class="btn btn-light"
              >
                <i class="fa-solid fa-passport"></i>
                {{ __("Change Permission") }}
              </DialogLink>
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
          <div class="sticky-top profile-photo-holder d-none d-md-block">
            <div class="card">
              <div class="card-profile-cover">
                <img
                  :alt="user.full_name"
                  :src="user.small_avatar"
                  class="card-img-top user-avatar"
                />
              </div>
              <div class="mx-auto">
                <img
                  alt="Image placeholder"
                  :src="user.small_avatar"
                  class="
                    card-profile-image
                    avatar
                    rounded-circle
                    shadow
                    hover-shadow-lg
                    user-avatar
                  "
                />
              </div>
              <div class="card-body p-3 pt-0 text-center">
                <h5 class="mb-0">{{ user.full_name }}</h5>                

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
  props: ["user"],
  methods: {
    destroy() {
      this.deleteConfirmDialog(
        this,
        route("admin.users.destroy", this.user.uuid)
      );
    },
  },
};
</script>