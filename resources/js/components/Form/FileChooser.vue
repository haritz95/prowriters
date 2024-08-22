<template>
  <div class="mb-3">
    <div class="form-label">
      {{ label }}
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
    </div>
    <div class="input-group input-group-sm mb-3">
      <input
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        :type="'text'"
        class="form-control form-control-sm"
        :class="{ 'is-invalid': $page.props.errors[name] }"
        :placeholder="placeholder"
        @focus="$page.props.errors[name] = null"
        id="inputGroupFile"
      />
      <button
        @click.stop.prevent="loadImageManager"
        class="input-group-text"
        for="inputGroupFile"
      >
        {{ __("Choose File") }}
      </button>
    </div>
    <ValidationError :name="name" />
  </div>
</template>

<script>
export default {
  props: ["modelValue", "label", "name", "placeholder", "required", "note", "tooltip"],
  emits: ["update:modelValue"],
  methods: {
    loadImageManager() {
      var scope = this;

      window.open(
        this.$page.props.auth_user.urls.image_manager,
        "fm",
        "width=800,height=600"
      );
      window.SetUrl = function (response) {
        // let image_url = new URL(response[0].url).pathname;
        // // "Replace all (/.../g) leading slash (^\/) or (|) trailing slash (\/$) with an empty string.
        // image_url = image_url.replace(/^\/|\/$/g, "");

        let image_url = new URL(response[0].url).pathname;
       
        image_url = image_url.replace("//", "/");
     
        scope.$emit("update:modelValue", image_url);
        scope.$emit("change", image_url);
      };
    },
  },
};
</script>
