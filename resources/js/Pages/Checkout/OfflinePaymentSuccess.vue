<template>
  <AppHead :title="data.title" />
  <div class="container text-center">
    <PageTitle :title="data.title" />
    <div class="row">
      <div class="col-md-12">
        <p class="pre-formatted"><i class="fa-solid fa-circle-check text-success"></i> {{ data.message }}</p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["data"],
};
</script>