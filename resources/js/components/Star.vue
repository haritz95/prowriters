<template>
  <div class="rating d-flex justify-content-between flex-sm-row flex-column">
    <div>
      <span class="star" v-for="(rating, index) in ratings" :key="index">
        <i
          :class="{
            'star-filled': number >= rating && number != null,
            'is-disabled': false,
          }"
          class="fas fa-star"
        ></i>
      </span>
    </div>
  </div>
  <div class="mt-2" v-html="comment"></div>
</template>

<script>
export default {
  props: ["number", "comment"],
  data: function () {
    return {
      ratings: [1, 2, 3, 4, 5],
    };
  },
};
</script>
