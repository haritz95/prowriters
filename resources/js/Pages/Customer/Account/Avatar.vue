<template>
  <AppHead :title="data.title" />
  <AccountLayout :customer="user">
    <div class="card">
      <div class="card-header h5">
        {{ data.title }}
      </div>
      <div class="card-body">
        <UploadImage :data="data" :url="route('customer.account.avatar.update')" />
      </div>
    </div>
  </AccountLayout>
</template>

<script>
import AccountLayout from "./Partials/AccountLayout.vue";
import { UploadImage } from "../../../components/Form/Index.js";

export default {
  props: ["data", "user"],
  components: {
    AccountLayout,
    UploadImage,
  }, 
};
</script>
