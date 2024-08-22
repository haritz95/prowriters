<template>
  <Flash />
  <Nav v-if="$page.props.auth_user" />
  <MenuForUnauthenticatedUser v-else />
  <slot />
  <Modal />
  <div class="container mt-4 mb-4">
    <div class="row">
      <div class="col-md-12 text-center">
        &copy; {{  $page.props.company.name }} {{ (new Date()).getFullYear() }}
      </div>
    </div>
  </div>
</template>

<script>
import Nav from "./Nav.vue";
import MenuForUnauthenticatedUser from "./MenuForUnauthenticatedUser.vue";
import Flash from "./Flash.vue";
import { Modal } from "momentum-modal";
export default {
  components: {
    Nav,
    Flash,
    MenuForUnauthenticatedUser,
    Modal,
  },
};
</script>