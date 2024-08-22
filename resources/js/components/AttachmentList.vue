<template>
  <div
    class="border p-2 mb-2"
    v-for="(attachment, index) in attachments"
    :key="index"
  >
    <div class="d-flex">
      <div
        class="flex-shrink-1 me-2"
        v-html="getFileIcon(attachment.display_name)"
      ></div>

      <a :href="route('attachments.download', attachment.uuid)">
        {{ attachment.display_name }}
      </a>
    </div>
  </div>
</template>
  <script>
export default {
  props: ["attachments"],
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