<template>
  <AppHead :title="data.title" />
  <ProfileLayout :user="user">
    <table class="table table-bordered mt-4">
      <tr>
        <td class="text-muted text-left">{{ __("ID") }}</td>
        <td class="text-left">{{ user.code }}</td>
      </tr>
      <tr>
        <td class="text-muted text-left">{{ __("Email") }}</td>
        <td class="text-left">{{ user.email }}</td>
      </tr>
      <tr>
        <td class="text-muted text-left">{{ __("Phone") }}</td>
        <td class="text-left">{{ user.phone }}</td>
      </tr>
      <tr>
        <td class="text-muted text-left">{{ __("Joined") }}</td>
        <td class="text-left">{{ localDateTime(user.created_at) }}</td>
      </tr>
      <tr>
        <td class="text-muted text-left">{{ __("Role") }}</td>
        <td class="text-left">{{  data.role_name  }}</td>
      </tr>
    </table>

  </ProfileLayout>
</template>

<script>
import ProfileLayout from "./Partials/ProfileLayout.vue";
export default {
  props: ["data", "user"],
  components: {
    ProfileLayout,
  },
};
</script>