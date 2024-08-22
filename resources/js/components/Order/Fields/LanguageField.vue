<template>
  <div class="mb-3">
    <label class="form-label">{{ __("Language") }}</label>
    <v-select
      :reduce="(language) => language.id"
      v-model="$store.state.form.language_id"
      :options="$store.state.data.content_writing_languages"
      label="name"
      :clearable="false"
      :searchable="true"
    ></v-select>

    <ErrorField :name="'language_id'" />
  </div>
</template>

<script>
export default {
  inject: ["$store"],
};
</script>