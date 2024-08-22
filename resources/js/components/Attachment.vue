<template>
  <div>
    <div id="dropzone">
      <div class="dropzone">
        <div class="dz-message needsclick">
          <div id="message">
            <span class="text">
              <i class="fas fa-upload"></i>
              <div>{{ __("Drop files here or click to upload") }}</div>
            </span>
            <span class="plus">+</span>
          </div>
        </div>
      </div>
    </div>
    <div v-if="config" class="text-muted">
      <small>{{ __("Max file size") }} : {{ config.maximum_file_size }}</small>
      <small
        >, {{ __("Max number of files") }} :
        {{ config.maximum_number_of_files_to_upload }}</small
      >
      <p>
        <small>{{ __("File types") }} : {{ config.allowed_file_extensions }}</small>
      </p>
    </div>
  </div>
</template>

<script>
import Dropzone from "dropzone";
import DropzoneStyles from "dropzone/dist/dropzone.css";

export default {
  mounted() {
    var scope = this;
    // Dropzone.autoDiscover = false;
    if (document.getElementsByClassName("dropzone")) {
      scope.dropzone = new Dropzone(".dropzone", {
        headers: {
          // "x-csrf-token": document.head.querySelector('meta[name="csrf-token"]')
          //   .content,
          "x-csrf-token": this.$page.props.csrf_token,
        },
        init: function () {
          let myDropzone = this;

          if (scope.existing_files && scope.existing_files.length > 0) {
            scope.existing_files.forEach(function (file) {
              // If you only have access to the original image sizes on your server,
              // and want to resize them in the browser:
              //myDropzone.displayExistingFile(file, file.url);              
              //myDropzone.displayExistingFile(file, scope.storageAsset(file.name));
            });

            // If you use the maxFiles option, make sure you adjust it to the
            // correct amount:
            // The number of files already uploaded
            myDropzone.options.maxFiles =
              myDropzone.options.maxFiles - scope.existing_files.length;
          }
        },

        url: this.upload_attachment_url,
        maxFiles: this.maximum_number_of_files_to_upload,
        maxFilesize: this.maximum_file_size,
        acceptedFiles: this.allowed_file_extensions,
        addRemoveLinks: true,
        // dictDefaultMessage: "Upload file",
        // dictRemoveFile: "Remove file",
        // dictCancelUpload: "Cancel upload",
        // dictFileTooBig: "File is too big",
        // dictInvalidFileType: "Invalid file type",
        // dictResponseError: "Server responded with {{statusCode}} code",
        // dictCancelUploadConfirmation: "Are you sure you want to cancel this upload?",
        // dictRemoveFileConfirmation: "Are you sure you want to remove this file?",
        // dictMaxFilesExceeded: "You can not upload any more files",
        // dictFallbackMessage: "Your browser does not support drag'n'drop file uploads.",
        // dictFallbackText:
        //   "Please use the fallback form below to upload your files like in the olden days.",
      })
        .on("success", this.onUpload)
        // .on("addedfile", this.addedFile)
        .on("removedfile", this.removeFile)
        // .on("complete", function (file) {
        //   this.removeAllFiles(true);
        // })
        .on("error", function (file) {
          scope.dropzone.removeFile(file);
        });
    }
  },
  props: {
    existing_files: {
      type: Array,
      default: () => [],
    },
    upload_attachment_url: String,
    maximum_file_size: Number,
    maximum_number_of_files_to_upload: Number,
    allowed_file_extensions: String,
    config: Object,
  },
  watch: {
    clear_existing_files: function (value) {
      if (value) {
        this.dropzone.removeAllFiles(true);
      }
    },
  },
  data: function () {
    return {
      files: this.existing_files ? JSON.parse(JSON.stringify(this.existing_files)) : [],
      dropzone: null,
    };
  },
  methods: {
    btnClicked() {
      this.dropzone.hiddenFileInput.click();
    },
    onUpload(file, data) {
      //file.previewElement.querySelector("img").src = 'domain.com/img/PDF_icon.svg.png';

      this.files.push(data);
      this.$emit("onChange", this.files);
    },
    removeFile(file) {
      var index = this.$data.files
        .map(function (row, index) {
          if (row.display_name == file.name) {
            return index;
          }
        })
        .filter(isFinite)[0];

      this.$data.files.splice(index, 1);
      this.$emit("onChange", this.$data.files);
    },
  },
};
</script>

<style lang="scss">
.dropzone {
  width: 98%;
  margin: 1%;
  border: 2px dashed #3498db !important;
  border-radius: 5px;
  transition: 0.2s;
}

.dropzone.dz-drag-hover {
  border: 2px solid #3498db !important;
}

.dz-message.needsclick img {
  width: 50px;
  display: block;
  margin: auto;
  opacity: 0.6;
  margin-bottom: 15px;
}

span.plus {
  display: none;
}

.dropzone.dz-started .dz-message {
  display: inline-block !important;
  width: 120px;
  float: right;
  border: 1px solid rgba(238, 238, 238, 0.36);
  border-radius: 30px;
  height: 120px;
  margin: 16px;
  transition: 0.2s;
}
.dropzone.dz-started .dz-message span.text {
  display: none;
}
.dropzone.dz-started .dz-message span.plus {
  display: block;
  font-size: 70px;
  color: #aaa;
  line-height: 110px;
}
</style>
