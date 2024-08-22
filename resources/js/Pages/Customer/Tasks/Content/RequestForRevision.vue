<template>
  <Modal :title="data.title" size="regular">
   <label>{{ __('Enter you message') }}</label>
   <SendMessage :config="data.config" />    
  </Modal>
</template>
<script>
import SendMessage from "../../../../components/SendMessage.vue";
export default {
  components: {
    SendMessage,
  },
  props: ["task", "data"],
};
</script>
