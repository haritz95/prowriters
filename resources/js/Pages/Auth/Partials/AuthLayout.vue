<template>
  <div class="container-fluid">
    <div class="row">
      <div
        class="col-md-4 order-2 order-md-1"
        :style="{ background: data.welcome_background_color }"
      >
        <div class="vh-100 p-5">
          <h1 v-html="data.welcome_title"></h1>
          <p v-if="data.welcome_sub_title" v-html="data.welcome_sub_title"></p>
          <img
            style="max-width: 300px"
            :src="asset(data.welcome_image)"
            class="img-fluid"
            :alt="asset(data.welcome_image_alt_text)"
          />
        </div>
      </div>
      <div class="offset-md-1 col-md-5 order-1 order-md-2">
        <slot />
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["data"],
};
</script>
