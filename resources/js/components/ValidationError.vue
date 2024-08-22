<template>
  <div class="invalid-feedback d-block" v-if="$page.props.errors[name]">
    {{ $page.props.errors[name] }}
  </div>
</template>

<script>
export default { 
  props: ["name"],
};
</script>

