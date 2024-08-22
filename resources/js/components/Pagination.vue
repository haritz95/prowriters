<template>
  <nav v-if="total != 0 && links.length != 3">
    <ul class="pagination">
      <li
        class="page-item"
        v-for="(link, index) in links"
        :key="index"
        :class="{ disabled: !link.url, active: link.active }"       
      >
        <Link
          :is="'Link'"
          :href="link.url"
          v-html="link.label"
          class="page-link"      
          :only="only"    
        />
      </li>
    </ul>
  </nav>
  <div v-if="total == 0" class="text-center">{{ __("No record found") }}</div>
</template>

<script>
export default {
  props: ["links", "total", "only"],
};
</script>