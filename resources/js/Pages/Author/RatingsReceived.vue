<template>
  <AppHead :title="data.title" />
  <div class="container page-container">
    <PageTitle :title="data.title" />
    <div class="row">    
      <div class="col-md-12">        
        <div        
          class="border mb-4 p-4"
          v-for="(rating, index) in ratings.data"
          :key="index"
        >
          <div class="row mb-2">
            <div class="col-md-6">
              {{ __("Associated Task") }} :
              <Link :href="route('author.tasks.show', rating.task.uuid)">{{
                rating.task.number
              }}</Link>
            </div>
            <div class="col-md-6 text-end">
              <small class="text-muted">{{
                localDateTime(rating.created_at)
              }}</small>
            </div>
          </div>
          <Star :number="rating.number" :comment="rating.comment" />
        </div>
        <Pagination
          v-if="ratings.total > 0"
          :total="ratings.total"
          :links="ratings.links"
        />
        <div v-if="ratings.total == 0" class="text-center">
          {{ __("No record found") }}
        </div>
      </div>
    </div>
  </div>
</template>
  
  <script>
import Pagination from "../../components/Pagination.vue";
import Star from "../../components/Star.vue";

export default {
  components: {
    Pagination,
    Star,
  },
  props: ["data", "ratings", "filters"],
  data() {
    return {
      tableOptions: {
        titles: [
          {
            name: this.__("Details"),
            className: "col-md-3",
          },
          {
            name: this.__("Deadline"),
            className: "col-md-5",
          },
          {
            name: this.__("Status"),
            className: "col-md-2",
          },
          {
            name: this.__("Earnings"),
            className: "col-md-2 text-end",
          },
        ],
      },
    };
  },
};
</script>
  