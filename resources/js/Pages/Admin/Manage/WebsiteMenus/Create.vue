<template>
  <Modal :title="data.title">
    <form
      @submit.prevent="
        existing_record
          ? form.patch(
              route('admin.manage.content.websiteMenus.update', [
                content_language,
                existing_record.id,
              ])
            )
          : form.post(
              route('admin.manage.content.websiteMenus.store', content_language)
            )
      "
    >    
      <Select
        :options="data.dropdowns.positions"
        v-model="form.position"
        :label="__('Menu Position')"
        :required="true"
        name="position"
      />
      
      <Select
        :options="parent_menus"
        v-model="form.parent_id"
        :label="__('Parent Menu')"       
        name="parent_id"
      />

      <Input
        v-model="form.name"
        name="name"
        :label="__('Name')"
        :required="true"
      />

      <Select
        :options="data.dropdowns.custom_pages"
        v-model="form.website_page_id"
        :label="__('Custom Page')"
        name="website_page_id"
      /> 

      <SubmitButton :disabled="form.processing" />
    </form>
  </Modal>
</template>

<script>
import {
  Input,
  Select,
  SubmitButton,
} from "../../../../components/Form/Index.js";

export default {
  components: {
    Input,
    Select,
    SubmitButton,
  },
  props: ["data", "content_language", "existing_record"],
  watch: {
    "form.position": {
      handler(newValue, oldValue) {        
        this.form.parent_id = null;
        this.parent_menus = this.data.dropdowns.parent_menus[newValue];
        
      },
      deep: true,
    },
  },
  data() {
    return {
      parent_menus : [],
      form: this.$inertia.form(this.prepareForm()),
    };
  },
  methods: {
    prepareForm() {
      let inputs = {
        parent_id: null,
        name: null,
        position: null,
        sequence_number: null,
        website_page_id: null,
      };
      if (this.existing_record) {
        inputs = { ...inputs, ...this.existing_record };
      }
      return inputs;
    },
  },
};
</script>