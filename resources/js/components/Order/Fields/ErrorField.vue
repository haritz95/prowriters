<template>
  <div class="invalid-feedback d-block" v-if="$store.state.errors[name]">
    {{ $store.state.errors[name] }}
  </div>
</template>

<script>
export default {
  inject: ["$store"],
  props: ["name"],
};
</script>
