<template>
  <section class="d-flex align-items-end navbar-background">
    <div class="container pt-4 pt-lg-0">
      <div class="row">
        <div class="col-lg-12 text-center">
          <div class="mb-4 mt-4">
            <div class="mx-auto">
              <img
                :alt="customer.full_name"
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

            <span class="h2 mb-0 text-white d-block">{{
              customer.full_name
            }}</span>
            <div class="text-white">
              {{ customer.code }}
            </div>
            <div></div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="pt-md-5 pt-lg-0">
    <div class="container page-container">
      <div class="row mt-4">
        <div class="col-md-4">
          <ul class="list-group sticky-top">
            <li class="list-group-item">
              <div>
                <Link :href="route('customer.account.edit')">{{
                  __("General")
                }}</Link>
              </div>
            </li>
            <li class="list-group-item">
              <div>
                <Link :href="route('customer.account.location')">{{
                  __("Location")
                }}</Link>
              </div>
            </li>   

            <li class="list-group-item">
              <div>
                <Link :href="route('customer.account.avatar')">{{
                  __("Avatar")
                }}</Link>
              </div>
            </li>
            <li class="list-group-item">
              <div>
                <Link :href="route('customer.account.password')">{{
                  __("Change Password")
                }}</Link>
              </div>
            </li>
            <li class="list-group-item" v-if="!$page.props.is_single_language">
              <div>
                <Link :href="route('customer.account.language')">{{
                  __("Change Language")
                }}</Link>
              </div>
            </li>
          </ul>
        </div>

        <div class="col-md-8">
          <slot />
        </div>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  props: ["customer"],
};
</script>