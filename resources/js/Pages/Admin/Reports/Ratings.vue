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

        <div class="row">
          <div class="col-md-6 mb-3 mb-md-0">
            <div>{{ __('Customer') }} : <Link :href="route('admin.customers.show', rating.task.customer.uuid)">{{ rating.task.customer.full_name }}</Link></div>
          </div>

          <div class="col-md-6 text-start text-md-end">
            <div>{{ __('Author') }} : <Link :href="route('admin.authors.show', rating.task.author.uuid)">{{ rating.task.author.full_name }}</Link></div>
          </div>

          <div class="col-md-12">
            <hr/>
          </div>

        </div>
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
import Pagination from "../../../components/Pagination.vue";
import Star from "../../../components/Star.vue";

export default {
  components: {
    Pagination,
    Star,
  },
  props: ["data", "ratings"],
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
  