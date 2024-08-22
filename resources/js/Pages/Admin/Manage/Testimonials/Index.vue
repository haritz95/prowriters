<template>
  <AppHead :title="data.title" />
  <ManageContentLayout :content_language="content_language" :title="data.title">
    <template v-slot:action>
      <AddButton
        :href="
          route('admin.manage.content.testimonials.create', content_language)
        "
      />
    </template>

    <Table
      :options="tableOptions"
      :links="testimonials.links"
      :total="testimonials.total"
    >
      <template v-slot>
        <tr v-for="(testimonial, index) in testimonials.data" :key="index">
          <td>
            <Link
              :href="
                route('admin.manage.content.testimonials.edit', [
                  content_language,
                  testimonial.id,
                ])
              "
              >{{ testimonial.name }}</Link
            >
            <div class="text-muted"><small>{{ testimonial.position }}</small></div>
          </td>
          <td>
            {{ testimonial.rating }}
          </td>
          <td class="col-md-2 text-end">
            <DestroyButton
              :delete_url="
                route('admin.manage.content.testimonials.destroy', [
                  content_language,
                  testimonial.id,
                ])
              "
            />
          </td>
        </tr>
      </template>
    </Table>
  </ManageContentLayout>
</template>

<script>
import ManageContentLayout from "../Partials/ManageContentLayout.vue";

import SearchBar from "../../../../Shared/SearchBar.vue";

import DestroyButton from "../../../../components/Form/DestroyButton.vue";

import Table from "../../../../components/Table.vue";

export default {
  components: {
    ManageContentLayout,
    SearchBar,
    Table,

    DestroyButton,
  },
  props: ["data", "content_language", "testimonials", "filters"],
  data() {
    return {
      tableOptions: {
        titles: [
          {
            name: this.__("Name"),
            className: "col-md-6",
          },
          {
            name: this.__("Rating"),
            className: "col-md-4",
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