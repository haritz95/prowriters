<template>
  <button
    v-if="!onProgress"
    class="btn btn-sm btn-light"
    @click="showCropper = true"
  >
    <i class="fa-solid fa-image"></i> {{ __("Select an image") }}
  </button>
  <div v-if="onProgress" class="text-center text-muted">
    {{ __("Uploading") }} ...
  </div>
  <avatar-cropper
    v-model="showCropper"
    :upload-handler="uploadHandler"
    :output-options="outputOptions"
  />
</template>

<script>
import AvatarCropper from "vue-avatar-cropper";

export default {
  props: ["data", "url"],
  components: {
    AvatarCropper,
  },
  data() {
    return {
      showCropper: null,
      outputOptions: {
        width: 360,
        height: 360,
      },
      onProgress: false,
    };
  },

  methods: {
    uploadHandler(cropper) {
      let file = cropper.getCroppedCanvas().toDataURL(this.cropperOutputMime);

      file = file.replace("data:image/png;base64,", "");

      this.$inertia.patch(
        this.url,
        { file: file },
        {
          onStart: (visit) => {
            this.onProgress = true;
          },
          onFinish: (visit) => {
            this.onProgress = false;
          },
        }
      );
    },
  },
};
</script>