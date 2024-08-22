<template>
  <section class="d-flex align-items-end navbar-background">
    <div class="container pt-4 pt-lg-0">
      <div class="row justify-content-end">
        <div class="col-lg-9">
          <div class="d-flex justify-content-between mb-4 mt-4">
            <span class="h2 mb-0 text-white d-block">{{
              customer.full_name
            }}</span>
            <div>
              <span class="badge bg-secondary me-2">{{ __("Customer") }}</span>
              <span v-if="customer.inactive" class="badge bg-danger">{{
                __("Inactive")
              }}</span>
            </div>
          </div>

          <div class="btn-group flex-wrap" role="group">
            <Link
              class="btn btn-light"
              :href="route('admin.customers.show', customer.uuid)"
            >
            <i class="fa-solid fa-house-user"></i> {{ __('Profile') }}
            </Link>
            <DialogLink
              :href="route('admin.customers.edit', customer.uuid)"
              class="btn btn-light"
            >
              <i class="fa-solid fa-pen-to-square"></i>
              {{ __("Edit Profile") }}
            </DialogLink>

            <DialogLink
              :href="route('admin.customers.password', customer.uuid)"
              class="btn btn-light"
            >
              <i class="fa-solid fa-key"></i>
              {{ __("Change Password") }}
            </DialogLink>

            <DialogLink
              :href="
                route('admin.customers.wallets.adjust.create', customer.uuid)
              "
              class="btn btn-light"
            >
              <i class="fa-solid fa-wallet"></i>
              {{ __("Adjust Wallet") }}
            </DialogLink>

            <div class="btn-group" role="group">
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
                    :href="route('admin.customers.tasks', customer.uuid)"
                    >{{ __("Tasks") }}</DialogLink
                  >
                </li>
                <li>
                  <DialogLink
                    class="dropdown-item"
                    :href="route('admin.customers.payments', customer.uuid)"
                    >{{ __("Payments") }}</DialogLink
                  >
                </li>
                <li>
                  <DialogLink
                    class="dropdown-item"
                    :href="
                      route(
                        'admin.customers.wallets.transactions',
                        customer.uuid
                      )
                    "
                    >{{ __("Wallet Transactions") }}</DialogLink
                  >
                </li>
              </ul>
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
            <div class="card">
              <div class="card-profile-cover">
                <img
                  :alt="customer.full_name"
                  :src="customer.small_avatar"
                  class="card-img-top customer-avatar"
                />
              </div>
              <div class="mx-auto">
                <img
                  alt="Image placeholder"
                  :src="customer.small_avatar"
                  class="
                    card-profile-image
                    avatar
                    rounded-circle
                    shadow
                    hover-shadow-lg
                    customer-avatar
                  "
                />
              </div>
              <div class="card-body p-3 pt-0 text-center">
                <h5 class="mb-0">{{ customer.full_name }}</h5>

                <div class="mt-4">
                  <DialogLink
                    :href="route('admin.customers.avatar', customer.uuid)"
                    class="btn btn-link"
                  >
                    <i class="fa-solid fa-image"></i>
                    {{ __("Change Avatar") }}
                  </DialogLink>
                </div>
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
        <div class="col-lg-9 mt-2 page-container">
          <!-- Account navigation -->

          <slot />
        </div>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  props: ["customer"],
  methods: {
    destroy() {
      this.deleteConfirmDialog(
        this,
        route("admin.customers.destroy", this.customer.uuid)
      );
    },
  },
};
</script>