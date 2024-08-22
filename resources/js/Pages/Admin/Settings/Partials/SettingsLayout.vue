<template>
  <AppHead :title="title + ' ' + __('Settings')" />
  <div class="container mt-4 page-container">
    <div class="row">
        <div class="col-md-3 border-end">
         <h4 class="mb-0">{{ __('Settings') }}</h4>  
         <small class="form-text text-muted">{{ __('Version')}} : {{ $page.props.version }}</small>
         <div class="sticky-top settings-menu">
             <Menu/>
        </div>      
      </div>
      <div class="col-md-9">
        <div class="ps-4">
          <slot />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Menu from './Menu.vue';
export default {
  props: ["title"],
  components: {
    Menu,
  },
};
</script>