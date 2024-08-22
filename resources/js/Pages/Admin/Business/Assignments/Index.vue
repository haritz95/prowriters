<template>
  <BusinessLayout :title="data.title">
   
    <TitleBar
      :title="data.title"
      :create_link="route('admin.assignments.create', data.service.slug)"
      :previous_link="route('admin.services.configurationHome', data.service.slug)"
      :previous_link_text="__('Back to configuration')"
    />
    <Search
      :url="route('admin.assignments.index', data.service.slug)"
      :filters="filters.filters"
      :services="data.services"
    />

    <Table
      :options="tableOptions"
      :links="assignments.links"
      :total="assignments.total"
      :tableStyle="'fs-8'"
    >
      <template v-slot>
        <tr v-for="(assignment, index) in assignments.data" :key="index">
          <td>
            <Link
              :href="
                route('admin.assignments.edit', {
                  service: data.service.slug,
                  assignment: assignment.slug,
                })
              "
              >{{ assignment.name }}</Link
            >
          </td>
          
          <!-- <td class="text-end">
            {{ formatMoney(assignment.price) }}
          </td> -->
          <td class="text-end">
            <Link
              class="btn btn-sm btn-light"
              :href="
                route('admin.assignments.units.create', {
                  service: data.service.slug,
                  assignment: assignment.slug,
                })
              "
              >{{ __('Set up price') }}</Link
            >
          </td>
          <td class="col-md-2 text-end">
            <DestroyButton
              :delete_url="
                route('admin.assignments.destroy', {
                  service: data.service.slug,
                  assignment: assignment.slug,
                  _query: filters,
                })
              "
            />
          </td>
        </tr>
      </template>
    </Table>
  </BusinessLayout>
</template>

<script>
import BusinessLayout from "../Partials/BusinessLayout.vue";
import Search from "./Search.vue";
import TitleBar from "../Partials/TitleBar.vue";
import DestroyButton from "../../../../components/Form/DestroyButton.vue";

import Table from "../../../../components/Table.vue";

export default {
  components: {
    BusinessLayout,
    Search,
    Table,
    TitleBar,
    DestroyButton,
  },
  props: ["data", "assignments", "filters"],
  data() {
    return {
      tableOptions: {
        titles: [
          {
            name: this.__("Name"),
            className: "col-md-6",
          },
          
          {
            name: this.__("Price"),
            className: "col-md-4 text-end",
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
