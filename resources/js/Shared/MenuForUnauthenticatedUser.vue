<template>
  <nav class="navbar navbar-expand-md shadow-sm navbar-background">
    <div class="container">
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
        <ul class="navbar-nav me-auto"></ul>
        <ul class="navbar-nav ml-auto">
          <li class="nav-item dropdown" v-if="!$page.props.is_single_language">
            <button
              class="btn btn-link dropdown-toggle caret-off"
              type="button"
              data-bs-toggle="dropdown"
              aria-expanded="false"
            >
              <span :class="'fi fi-' + $page.props.current_country_code"></span>
            </button>
            <ul class="dropdown-menu">
              <li v-for="(language, index) in $page.props.system_languages" :key="index">
                <a
                  class="dropdown-item"
                  :href="route('public.changeLanguage', { switchTo: language.iso_code })"
                  ><span :class="'fi fi-' + language.country_code"></span>
                  {{ language.name }}</a
                >
              </li>
            </ul>
          </li>

          <li
            class="nav-item"
            v-if="!$page.props.disable_order_page_for_unauthenticated_user"
          >
            <Link class="nav-link" :href="route('customer.tasks.create')">{{
              __("Order")
            }}</Link>
          </li>
          <li
            class="nav-item"
            v-if="!$page.props.disable_order_page_for_unauthenticated_user"
          >
            <Link class="nav-link" :href="route('public.bidRequests.services')">{{
              __("Place Bid Request")
            }}</Link>
          </li>
          <li
            class="nav-item"
            v-if="!$page.props.hide_author_application_link_from_website"
          >
            <Link class="nav-link" :href="route('public.author.application.create')">{{
              __("Become an Author")
            }}</Link>
          </li>
          <li class="nav-item">
            <Link class="nav-link" :href="route('login')">{{ __("Login") }}</Link>
          </li>
          <li class="nav-item">
            <Link class="nav-link" :href="route('register')">{{ __("Register") }}</Link>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script>
export default {
  components: {
    // CustomerMenu,
    // AdminMenu,
    // AuthorMenu,
    // Notification,
    // LanguageSelector,
  },
  created() {
    this.menus = this.$page.props.main_menu;
  },
  data() {
    return {
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
