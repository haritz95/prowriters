<template>
   <AppHead :title="data.title" />
   <div class="container page-container">
      <div class="row justify-content-center">
         <div class="col-md-12">
            <h4>{{ task.number }} - {{ data.title }}</h4>
            <hr>
         </div>
         <div class="col-md-8">
            <p class="bg-light text-dark p-2">{{ data.description }}</p>
            <form @submit.prevent="form.post(route('customer.tasks.ratings.store', task.uuid))">
               <div class="mb-3">
                  <label class="form-label">{{ __('Your rating') }}</label>
                  <StarRating v-model="form.number" :rating_description="data.rating_description"/>
                  <ValidationError name="number" />
               </div>
               <div class="mb-3">
                  <label class="form-label">{{ __('Your comment') }}</label>
                  <textarea class="form-control" v-model="form.comment"></textarea>
                  <ValidationError name="comment" />
               </div>
               <button :disabled="form.processing" type="submit" class="btn btn-success btn-sm">
               <i class="fas fa-check-circle"></i> {{ __('Submit') }}
               </button>
            </form>
         </div>
      </div>
   </div>
</template>

<script>

import StarRating from "../../../../components/StarRating.vue";

export default {
  props: ["task", "data"],
  components: { 
    StarRating,   
  },
  data() {
    return {
      form: this.$inertia.form({
        number: 0,
        comment : null
      }),
    };
  }, 
};
</script>