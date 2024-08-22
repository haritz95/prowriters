<template>
  <div class="rating d-flex justify-content-between flex-sm-row flex-column">
    <div>
        <a
        v-for="(rating, index) in ratings"
        :key="index"
        @mouseover="star_over(rating)"
        @mouseleave="star_out"
        @click.stop.prevent="set(rating)"
        href="#"
        >
        <i
            :class="{
            'star-filled': rating_value >= rating && rating_value != null,
            'is-disabled': disabled,
            }"
            class="fas fa-3x fa-star"
        ></i>
        </a>
    </div>
    <div v-if="rating_description">{{ rating_description[rating_value] }}</div>
  </div>
</template>

<script>
export default {
  props: ["modelValue", "rating_description", "disabled"],
  data: function () {
    return {
      temp_value: null,
      ratings: [1, 2, 3, 4, 5],
      rating_value: this.modelValue,   
    };
  },

  methods: {
    /*
     * Behaviour of the stars on mouseover.
     */
    star_over: function (index) {
      if (!this.disabled) {
        this.temp_value = this.rating_value;
        this.rating_value = index;
      }
    },

    /*
     * Behaviour of the stars on mouseout.
     */
    star_out: function () {
      if (!this.disabled) {
        this.rating_value = this.temp_value;
      }
    },

    /*
     * Set the rating.
     */
    set: function (value) {
      if (!this.disabled) {
        this.temp_value = value;
        this.rating_value = value;
        this.$emit("update:modelValue", value);
      }
    },
  },
};
</script>

<style>
</style>