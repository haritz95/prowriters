<template>
  <Modal :title="__('Send message')" size="regular">
    <SendMessage :config="data.config" />
  </Modal>
</template>

<script>
import SendMessage from "../../../../components/SendMessage.vue";
import Modal from "../../../../components/Modal.vue";

export default {
  props: ["task", "data"],
  components: {
    SendMessage,
    Modal,
  },
};
</script>