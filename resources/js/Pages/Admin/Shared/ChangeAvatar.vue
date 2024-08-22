<template>
  <Modal :title="data.title">
    <UploadImage :data="data" />
  </Modal>
</template>

<script>
import { UploadImage } from "../../../components/Form/Index.js";

export default {
  props: ["data"],
  components: {
    UploadImage,
  },
};
</script>