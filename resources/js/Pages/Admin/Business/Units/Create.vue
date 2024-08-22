<template>
  <Modal :title="data.title" size="full_screen">
    <table class="table table-sm" id="unitPrice">
      <thead class="table-secondary">
        <tr>
          <th class="col-md-3">{{ __("Name") }}</th>
          <th class="col-md-3">{{ __("Quantity") }}</th>
          <th class="col-md-2">{{ __("Price") }}</th>
          <th class="col-md-3">{{ __("Turnaround Time") }}</th>
          <th class="col-md-1"></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item, index) in form.units" :key="index">
          <td>
            <Input
              v-model="form.units[index].name"
              :name="getErrorFieldName('name', index)"
            />
          </td>
          <td>
            <Input
              v-model="form.units[index].quantity"
              :name="getErrorFieldName('quantity', index)"
              @keypress="onlyNumber($event, form.units[index].quantity)"
            />
          </td>
          <td>
            <Input
              v-model="form.units[index].price"
              :name="getErrorFieldName('price', index)"
              @keypress="onlyNumber($event, form.units[index].price)"
            />
          </td>
          <td>
            <Select
              :searchable="true"
              :options="data.dropdowns.urgencies"
              v-model="form.units[index].urgency_id"
              :name="getErrorFieldName('urgency_id', index)"
            />
          </td>
          <td class="text-end">
            <button
              type="button"
              @click="removeLine(index)"
              class="btn btn-sm btn-danger"
            >
              <i class="fa-solid fa-trash-can"></i>
            </button>
          </td>
        </tr>    
      </tbody>
    </table>

    <template v-slot:footer>
      <button type="button" @click="addNewLine" class="btn btn-sm btn-success">
              {{ __("Add new line") }}
            </button>

      <button
        @click="submitForm"
        :disabled="form.processing"
        type="submit"
        class="btn btn-sm btn-primary"
      >
        {{ __("Submit") }}
      </button>
    </template>
  </Modal>
</template>

<script>
import DestroyButton from "../../../../components/Form/DestroyButton.vue";

import { Input, Select, SubmitButton } from "../../../../components/Form/Index.js";

export default {
  components: {
    Input,
    DestroyButton,
    Select,
    SubmitButton,
  },
  props: ["data"],
  data() {
    return {
      form: this.$inertia.form(this.prepareForm()),
      formConfig: {
        //onSuccess: () => this.form.reset(),
      },
    };
  },
  methods: {
    submitForm() {
      this.form.post(this.data.urls.submit_form, this.formConfig);
    },
    prepareForm() {
      let inputs = {
        units: this.data.units ? this.data.units : [],
      };

      return inputs;
    },

    addNewLine() {
      this.form.units.push({
        id: null,
        name: "",
        urgency_id: "",
        price: 0,
        quantity: 0,
      });
    },
    removeLine(index) {
      this.form.units.splice(index, 1);
    },
    getErrorFieldName(field, index) {
      return "units." + [index] + "." + field;
    },
    restrictEmpty(field, index) {
      const value = this.form.units[index][field];
      if (!value || value == "") {
        this.form.units[index][field] = 0;
      }
    },
  },
};
</script>

<style lang="scss">
#unitPrice {
  .form-control-sm {
    min-height: 0;
    padding: 0;
  }
  .vs__dropdown-toggle {
    padding: 0;
  }

  .vs__search,
  .vs__search:focus {
    margin: 0;
  }
  .mb-3 {
    margin-bottom: 0 !important;
  }
}
</style>
