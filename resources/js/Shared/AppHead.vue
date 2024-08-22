<template>
  <Head :title="title ? `${title} - ` + $page.props.company.name : $page.props.company.name">
    <slot />
  </Head>
</template>

<script>
import { Head } from '@inertiajs/inertia-vue3'
export default {
  components: {
    Head,
  },
  props: {
    title: String,
  },
}
</script>