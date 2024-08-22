<template>
  <Modal :title="data.title" size="regular">  
    <SendMessage :config="data.config" />
  </Modal>
</template>

<script>
import TaskShowLayout from "../Partials/TaskShowLayout.vue";
import SendMessage from "../../../../components/SendMessage.vue";

export default {
  props: ["task", "data"],
  components: {
    TaskShowLayout,
    SendMessage,
  },
};
</script>