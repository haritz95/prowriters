<template>
  <AppHead :title="data.title" />
  <ManageContentLayout :content_language="content_language">
    <SearchBar
      :url="
        route('admin.manage.content.systemTranslations.index', content_language)
      "
      :filters="filters.filters"
      :hide_inactive_search="true"
    >
      <template v-slot:leftArea>
        <Link :href="route('admin.manage.content.systemTranslations.importTranslationTexts', content_language)" class="btn btn-sm btn-success me-2">{{ __("Import Translation Keys") }}</Link>
        <Link v-if="texts.total" :href="route('admin.manage.content.systemTranslations.applyTranslation', content_language)" class="btn btn-sm btn-primary">{{ __("Apply Translation") }}</Link>
      </template>
    </SearchBar>
    <div class="text-danger mt-4 mb-4"><small> *** {{ __('Do not translate the words starting with ":" semicolon') }}</small></div>
    <div v-for="(row, index) in texts.data" :key="index">
      <div class="mb-2 border-bottom pb-2 pt-2">
        <div>
          <TextArea
            :label="row.text"
            v-model="form.fields[row.key]"
            @change="saveTranslation(row.id, row.key, $event.target.value)"
          />
          <div>
            <small class="text-muted" :ref="'element' + row.id"></small>
          </div>
        </div>
      </div>
    </div>

    <Pagination
      v-if="texts.total > 0"
      :total="texts.total"
      :links="texts.links"
    />
    <div v-if="texts.total == 0" class="text-center">
      {{ __("No record found") }}
    </div>
  </ManageContentLayout>
</template>

<script>
import Pagination from "../../../../components/Pagination.vue";
import { TextArea } from "../../../../components/Form/Index.js";
import SearchBar from "../../../../Shared/SearchBar.vue";
import ManageContentLayout from "../Partials/ManageContentLayout.vue";
export default {
  props: ["data", "filters", "content_language", "texts"],
  components: {
    ManageContentLayout,
    Pagination,
    TextArea,
    SearchBar,
  },
  watch: {
    // Note: only simple paths. Expressions are not supported.
    "texts.data"(newValue) {
      this.form.fields = this.prepareForm();
    },
  },
  data() {
    return {
      form: this.$inertia.form({ fields: this.prepareForm() }),
    };
  },
  methods: {
    saveTranslation(id, key, value) {
      let scope = this;

      let element = scope.$refs["element" + id][0];
      element.innerHTML = scope.__("saving ...");

      axios
        .post(
          route(
            "admin.manage.content.systemTranslations.update",
            this.content_language
          ),
          {
            key: key,
            value: value,
          }
        )
        .then(function (response) {
          element.innerHTML =
            scope.__("saved at") + " " + new Date().toLocaleTimeString();
          element.style.display = "block";

          setTimeout(() => (element.style.display = "none"), 5000);
        });
    },
    prepareForm() {
      let form_inputs = {};
      for (let index = 0; index < this.texts.data.length; index++) {
        const row = this.texts.data[index];
        form_inputs[row.key] = row.translated_text;
      }
      return form_inputs;
    },
  },
};
</script>