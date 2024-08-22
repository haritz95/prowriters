<template>
  <Modal :title="data.title" size="small">
    <form @submit.prevent="form.patch(data.urls.submit_form)">
      <fieldset class="border rounded-3 p-3">
        <legend class="float-none w-auto px-3 fs-8">
          {{ __("Role") }}
        </legend>

        <Radio :options="data.roles" v-model="form.role" name="role" />

      </fieldset>

      <SubmitButton class="mt-4" :disabled="form.processing" />
    </form>
  </Modal>
</template>
  
  <script>
import { Radio, SubmitButton } from "../../../components/Form/Index.js";
export default {
  props: ["data"],
  components: {
    Radio,
    SubmitButton,
  },
  data() {
    return {
      form: this.$inertia.form({
        role: this.data.user_current_role,
      }),
    };
  },
  methods: {
    initializePermissionList() {
      let permissions = JSON.parse(JSON.stringify(this.data.permission_names));
      permissions["is_super_admin"] = null;
      Object.keys(permissions).forEach(function (name, index) {
        permissions[name] = null;
      });

      permissions = { ...permissions, ...this.data.permissions };
      return permissions;
    },
  },
};
</script>