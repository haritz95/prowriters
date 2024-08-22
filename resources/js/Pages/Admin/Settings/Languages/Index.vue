<template>
  <SettingsLayout :title="data.title">
    <ActionToolBar :toolbar="toolbar" />

    <Table :options="tableOptions" :links="languages.links" :total="languages.total">
      <template v-slot>
        <tr v-for="(language, index) in languages.data" :key="index">
          <td>
            <Link :href="route('admin.settings.systemLanguages.edit', language.id)">{{ language.name }}</Link>
          </td>   
          <td>{{ language.iso_code }}</td> 
          <td>{{ language.country_code }}</td> 
          <td>{{ language.layout_direction }}</td>  
          <td class="text-center">
            <span v-if="language.is_default"><i class="fa-solid fa-circle-check text-success"></i></span>
          </td>                    
          <td class="col-md-2 text-end">
            <DestroyButton :delete_url="route('admin.settings.systemLanguages.destroy', language.id)"/>
          </td>
        </tr>
      </template>
    </Table>
  </SettingsLayout>
</template>

<script>
import SettingsLayout from "../Partials/SettingsLayout.vue";
// import SearchBar from "../../../../../Shared/SearchBar.vue";

import DestroyButton from "../../../../components/Form/DestroyButton.vue";
import ActionToolBar from "../Partials/ActionToolBar.vue";

import Table from "../../../../components/Table.vue";

export default {
  components: {
    SettingsLayout,
    ActionToolBar,
    Table, 
    DestroyButton,
  },
  props: ["data", "languages", "filters"],
  data() {
    return {
      toolbar: {
        title: this.data.title,
        hide_save_button: true,
        links: {
          create: {
            title: this.__('Add new'),
            url: route('admin.settings.systemLanguages.create'),
          },
        },
      },
      tableOptions: {
        titles: [
        {
            name: this.__("Name"),
            className: "col-md-2",
          },          
          {
            name: this.__("Language ISO"),
            className: "col-md-2",
          },          
          {
            name: this.__("Country ISO"),
            className: "col-md-2",
          },          
          {
            name: this.__("Layout"),
            className: "col-md-2",
          },          
          {
            name: this.__("Default"),
            className: "col-md-2",
          },          
          {
            name: this.__("Action"),
            className: "col-md-2 text-end",
          },
        ],
      },
    };
  },
};
</script>