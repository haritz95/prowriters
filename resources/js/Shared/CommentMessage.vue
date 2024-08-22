<template>
  <div class="ps-4 pe-4 pt-2 pb-2 border-end">
    <div class="d-flex mb-2">
      <img
        class="avatar"
        :src="comment.user.small_avatar"
        :alt="comment.user.code"
        loading="lazy"
      />
      <div class="ps-2">
        <div>{{ comment.user.code }}</div>
        <small class="text-muted"
          >{{ __("Posted") }} : {{ localDateTime(comment.created_at) }}</small
        >
      </div>
    </div>
    <hr />
    <div class="">
      <div class="text-break" v-html="comment.message"></div>
      <div class="mt-3" v-if="comment.attachments.length > 0">
        <hr />
        <div class="mb-2">{{ __("Attachments") }}</div>
        <AttachmentList :attachments="comment.attachments"/> 
      </div>
    </div>
  </div>
</template>
<script>
import AttachmentList from '../components/AttachmentList.vue';

export default {
  props: ["comment"],
  components : {
   AttachmentList,
  },
  methods: {
    getFileIcon(filename) {
      let file_extension = filename.split(".").pop();
      let file_type = "file";
      let images = [
        "svg",
        "gif",
        "png",
        "psd",
        "jpg",
        "jpeg",
        "tif",
        "tiff",
        "bmp",
        "eps",
        "raw",
        "cr2",
        "nef",
        "orf",
        "sr2",
      ];

      let excel = ["xls", "xlsx"];
      let word = ["doc", "docx", "odt"];
      let powerpoint = ["ppts", "pptx"];
      let archives = ["zip", "rar", "7z", "tar"];

      let videos = [
        "webm",
        "mpg",
        "mp2",
        "mpeg",
        "mpe",
        "mpv",
        "ogg",
        "mp4",
        "m4p",
        "m4v",
        "avi",
        "wmv",
        "mov",
        "qt",
        "flv",
        "swf",
      ];
      let audios = ["mp3", "m4a", "aac", "oga", "ogg", "wav"];

      if (images.includes(file_extension)) {
        file_type = "image";
      } else if (excel.includes(file_extension)) {
        file_type = "excel";
      } else if (word.includes(file_extension)) {
        file_type = "word";
      } else if (powerpoint.includes(file_extension)) {
        file_type = "powerpoint";
      } else if (archives.includes(file_extension)) {
        file_type = "archive";
      } else if (videos.includes(file_extension)) {
        file_type = "video";
      } else if (audios.includes(file_extension)) {
        file_type = "audio";
      } else if (file_extension == "pdf") {
        file_type = "pdf";
      } else if (file_extension == "csv") {
        file_type = "csv";
      } else {
        file_type = "file";
      }

      let icons = {
        image: '<i class="fa-solid fa-file-image fs-4"></i>',
        excel: '<i class="fa-solid fa-file-excel fs-4"></i>',
        word: '<i class="fa-solid fa-file-word fs-4"></i>',
        powerpoint: '<i class="fa-regular fa-file-powerpoint fs-4"></i>',
        archive: '<i class="fa-regular fa-file-zipper fs-4"></i>',
        video: '<i class="fa-solid fa-file-video  fs-4"></i>',
        audio: '<i class="fa-solid fa-file-audio  fs-4"></i>',
        pdf: '<i class="fa-solid fa-file-pdf fs-4"></i>',
        csv: '<i class="fa-solid fa-file-csv fs-4"></i>',
        file: '<i class="fa-regular fa-file-lines fs-4"></i>',
      };
      return icons[file_type];
    },
  },
};
</script>