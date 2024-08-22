<template>
  <div v-bind:class="{ 'mb-3': label }">
    <label v-if="label" class="form-label"
      >{{ label }}
      <small v-if="note" class="text-muted">{{ note }}</small>
      <span v-if="required" class="ms-1 required">*</span>
      <span
        v-if="tooltip"
        class="ms-1"
        ref="tooltip"
        data-bs-toggle="tooltip"
        data-bs-placement="top"
        :title="tooltip"
        ><i class="fa-solid fa-circle-question"></i
      ></span>
    </label>
    <editor v-model="richEditorModel" :api-key="this.$page.props.auth_user.api_keys.tinymce" :init="config" />
  </div>
</template>
  <script>
import Editor from "@tinymce/tinymce-vue";

export default {
  props: ["modelValue", "height", "label", "note", "required", "tooltip"],
  components: {
    Editor,
  },
  emits: ["update:modelValue"],
  watch: {
    richEditorModel: function (newval, old) {
      this.$emit("update:modelValue", newval);
    },
  },
  data() {
    return {
      richEditorModel : this.modelValue,
      config: {
        path_absolute: this.$page.props.rich_editor_base_url,
        document_base_url: this.$page.props.rich_editor_base_url, 
        convert_urls: false,
        
        plugins: "link image code table directionality emoticons",
        toolbar:
          "table | undo redo | styleselect | forecolor backcolor removeformat | pagebreak | bold italic | alignleft aligncenter alignright alignjustify | outdent indent | link image | code | ltr rtl",
        branding: false,
        file_picker_callback: this.openFileManger,

        height: this.height ? this.height : "400" + "px",
        force_br_newlines: true,
        image_dimensions: false,
        image_class_list: [
          {
            title: this.__("Responsive"),

            value: "img-fluid",
          },
        ],
        table_class_list: [
          { title: "None", value: "table table-sm" },
          { title: "Stripe Rows", value: "table table-sm table-striped" },
          { title: "Hoverable Rows", value: "table table-sm table-hover" },
          {
            title: "Stripe & Hoverable Rows",
            value: "table table-sm table-striped table-hover",
          },
         
        ],
        
      },
    };
  },
  methods: {
    openFileManger(callback, value, meta) {
      tinymce.activeEditor.windowManager.openUrl({
        url:
          this.$page.props.auth_user.urls.image_manager +
          "&editor=" +
          meta.fieldname,

        title: this.__("File Manager"),

        onMessage: (api, message) => {
          api.close();

          let image_url = new URL(message.content).pathname;
          image_url = image_url.replace('//', '/');
          
          //"Replace all (/.../g) leading slash (^\/) or (|) trailing slash (\/$) with an empty string.
          //image_url = image_url.replace(/^\/|\/$/g, "");

          callback(image_url);
        },
      });
    },
  },
};
</script>