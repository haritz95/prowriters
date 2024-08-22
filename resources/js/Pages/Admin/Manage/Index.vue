<template>
  <AppHead :title="data.title" />
  <div class="container page-container">
    <PageTitle :title="data.title" />
    <div class="row">
      <div class="col-md-12">
        <ul class="list-group list-group-horizontal">
          <li
            class="list-group-item"
            v-for="(language, index) in data.languages"
            :key="index"
          >
            <Link :href="route('admin.manage.content.index', [language.iso_code])"
              ><i class="fi" :class="'fi-' + language.country_code"></i>
              {{ language.name }}</Link
            >
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>
  
  <script>
export default {
  props: ["data"],
};
</script>