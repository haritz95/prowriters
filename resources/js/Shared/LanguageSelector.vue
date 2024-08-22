<template>
  <li class="nav-item dropdown">
    <a
      class="nav-link dropdown-toggle"
      href="#"
      id="dropdown01"
      data-bs-toggle="dropdown"
      aria-expanded="false"
    >
      <span class="fi" :class="'fi-' + $page.props.current_country_code"></span>
    </a>
    <ul
      class="dropdown-menu dropdown-menu-end fs-8"
      aria-labelledby="dropdown01"
    >
      <li
        v-for="(language, index) in $page.props.system_languages"
        :key="index"
      >
        <button
          type="button"
          class="dropdown-item"
          @click="switchLanguage(language.iso_code)"
        >
          <div class="d-flex">
            <div class="fi" :class="'fi-' + language.country_code"></div>
            <div class="ps-1">{{ language.name }}</div>
          </div>
        </button>
      </li>
    </ul>
  </li>
</template>
<script>
export default {
  methods: {
    switchLanguage(iso_code) {
      let old_string = "/" + this.$page.props.current_locale;
      let new_string = "/" + iso_code;

      let url = new URL(window.location);

      let new_path_name = url.pathname.replace(old_string, new_string);
      let new_url = url.protocol + "//" + url.host + new_path_name + url.search;
      window.location.href = new_url;
    },
  },
};
</script>