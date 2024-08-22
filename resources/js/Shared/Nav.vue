<template>
  <nav class="navbar navbar-expand-md shadow-sm navbar-background">
    <div
      :class="
        ($page.props.auth_user.type == userTypes.customer )? 'container' : 'container-fluid'
      "
    >
      <a class="navbar-brand" :href="$page.props.base_url">
        <img
          class="logo"
          :src="$page.props.company.logo"
          :alt="$page.props.company.name"
        />
      </a>

      <button
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <i class="fas fa-bars"></i>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto">
          <CustomerMenu v-if="$page.props.auth_user.type == userTypes.customer" />
          <AdminMenu v-if="$page.props.auth_user.type == userTypes.admin" />
          <AuthorMenu v-if="$page.props.auth_user.type == userTypes.author" />
        </ul>
        <ul class="navbar-nav ml-auto">
          <Notification />

          <li class="nav-item dropdown" style="z-index: 2000 !important">
            <a
              class="nav-link dropdown-toggle"
              href="#"
              id="account"
              data-bs-toggle="dropdown"
              aria-expanded="false"
            >
              {{ $page.props.auth_user.name }}
            </a>

            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="account">
              <Link
                class="dropdown-item"
                :href="$page.props.auth_user.edit_my_account_link"
              >
                {{ __("My Account") }}
              </Link>
              <button class="dropdown-item" @click="logout">
                {{ __("Logout") }}
              </button>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script>
import CustomerMenu from "./Menus/Customer.vue";
import AdminMenu from "./Menus/Admin.vue";
import AuthorMenu from "./Menus/Author.vue";
import Notification from "./Notification.vue";
import LanguageSelector from "./LanguageSelector.vue";

export default {
  components: {
    CustomerMenu,
    AdminMenu,
    AuthorMenu,
    Notification,
    LanguageSelector,
  },
  created() {
    this.menus = this.$page.props.main_menu;
  },
  data() {
    return {
      userTypes: {
        admin: 1,
        customer: 2,
        author: 3,
      },
      company: {},
    };
  },
  methods: {
    logout() {
      this.$inertia.post(route("logout"));
    },
  },
};
</script>
