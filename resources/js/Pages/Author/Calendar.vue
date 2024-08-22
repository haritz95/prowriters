<template>
  <AppHead :title="data.title" />
  <div class="container page-container">
    <PageTitle :title="data.title" />
    <Calendar :events="data.events"/>
  </div>
</template>

      
  <script>
import Calendar from "../../components/Calendar.vue";

export default {
  components: {
    Calendar,
  },
  props: ["data"], 
};
</script>
    