<template>
  <AppHead :title="data.title" />
  <div class="container-fluid">
    <div class="row vh-100">
      <div class="col-md-4 vh-100" style="background: #f9f9f9">
        <ContentGenerator :data="data" :existing_record="existing_record" />
      </div>
      <div class="col-md-8 mt-4">
        <ContentList :data="data" :contents="contents" :filters="filters" />
      </div>
    </div>
  </div>
</template>

<script>
import ContentGenerator from "../../../components/AiContent/ContentGenerator.vue";
import ContentList from "../../../components/AiContent/ContentList.vue";

export default {
  components: {
    ContentList,
    ContentGenerator,
  },
  props: ["data", "contents", "filters", "existing_record"],
};
</script>
