<template>
  <AppHead :title="data.title" />
  <ManageContentLayout :content_language="content_language" :title="data.title">
    <form
      @submit.prevent="
        form.patch(
          route(
            'admin.manage.content.homepage.section.whyChooseUs.update',
            content_language
          )
        )
      "
    >
   
      <Input
        v-model="form.title"
        :label="__('Title')"
        name="title"
        :required="true"
      />

      <Input
        v-model="form.sub_title"
        :label="__('Sub Title')"
        name="sub_title"
      />

      <FileChooser
        v-model="form.image"
        :label="__('Image')"
        name="image"
        :required="true"
      />

      <div class="row">
        <div class="col-md-6">
          <Select
            v-model="form.image_position"
            :options="data.dropdowns.image_positions"
            :label="__('Image Position')"
            name="image_position"
            :required="true"
          />
        </div>
        <div class="col-md-6">
          <Input
            v-model="form.image_alt_text"
            :label="__('Image Alt Text')"
            name="image_alt_text"
          />
        </div>
      </div>

      <ColorPicker v-model="form.appearance.bg_color" :label="__('Background Color')" name="appearance.bg_color"/>
      <ColorPicker v-model="form.appearance.text_color" :label="__('Text Color')" name="appearance.text_color"/>
        

      <div
        class="card"
        v-for="(box, index) in form.additional_data"
        :key="index"
      >
        <div class="card-body">
          <div class="card-title h6">{{ __("Box") }} {{ index + 1 }}</div>
          <hr />
          <Input
            v-model="form.additional_data[index].title"
            :label="__('Title')"
            :required="true"
            :name="'additional_data.' + index + '.title'"
          />
          <TextArea
            v-model="form.additional_data[index].content"
            :label="__('Content')"
            :required="true"
            :name="'additional_data.' + index + '.content'"
          />

          <div class="row">
            <div class="col-md-6">
              <FileChooser
                v-model="form.additional_data[index].image"
                :label="__('Image')"
                :required="true"
                :name="'additional_data.' + index + '.image'"
              />
            </div>
            <div class="col-md-6">
              <Input
                v-model="form.additional_data[index].image_alt_text"
                :label="__('Image Alt Text')"
                :name="'additional_data.' + index + '.image_alt_text'"
              />
            </div>
          </div>

          <button
            type="button"
            class="btn btn-danger btn-sm"
            @click="deleteBox(index)"
          >
            <i class="far fa-trash-alt"></i>
          </button>
        </div>
      </div>

      <div class="card">
        <ul class="list-group list-group-flush">
          <li class="list-group-item">
            <button
              type="button"
              class="btn btn-sm btn-success"
              @click="addBox"
            >
              <i class="fas fa-plus"></i> {{ __("Add Box") }}
            </button>
          </li>
        </ul>
      </div>

      <SubmitButton :disabled="form.processing" />
    </form>
  </ManageContentLayout>
</template>
      
      <script>
import ManageContentLayout from "../Partials/ManageContentLayout.vue";
import {
  Input,
  TextArea,
  FileChooser,
  SubmitButton,
  Select,
  ColorPicker,
} from "../../../../components/Form/Index.js";
export default {
  props: ["data", "existing_record", "content_language"],
  components: {
    ManageContentLayout,
    Input,
    SubmitButton,
    TextArea,
    FileChooser,
    Select,
    ColorPicker,
  },
  data() {
    return {
      form: this.$inertia.form(this.prepareForm()),
      formConfig: {
        preserveScroll: true,
        onSuccess: () => this.form.reset(),
      },
    };
  },
  methods: {
    prepareForm() {
      let inputs = {
        title: null,
        sub_title: null,
        image: null,
        image_alt_text: null,
        image_position: null,
        appearance : {
          bg_color : null,
          text_color : null,
        },
        additional_data: [],
      };
      if (this.existing_record) {
        inputs = { ...inputs, ...this.existing_record };
      }
      return inputs;
    },
    addBox() {
      this.form.additional_data.push({
        title: null,
        content: null,
        image: null,
        image_alt_text: null,
      });
    },
    deleteBox(index) {
      if (this.form.additional_data.length > 1) {
        this.form.additional_data.splice(index, 1);
      }
    },
  },
};
</script>