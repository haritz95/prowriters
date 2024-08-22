<template>
  <AppHead :title="data.title" />
  <div class="container-fluid">
    <div class="row vh-100">
      <div class="col-md-4 vh-100" style="background: #f9f9f9">
        <ContentGenerator :data="data" :existing_record="existing_record" />
      </div>
      <div class="col-md-8">
        <ContentEditor :data="data" :existing_record="existing_record" />
      </div>
    </div>
  </div>
</template>

<script>
import ContentGenerator from "../../../components/AiContent/ContentGenerator.vue";
import ContentEditor from "../../../components/AiContent/ContentEditor.vue";

export default {
  props: ["data", "existing_record"],
  components: {
    ContentGenerator,
    ContentEditor,
  },
};
</script>
