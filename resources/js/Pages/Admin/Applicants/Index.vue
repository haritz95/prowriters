<template>
  <AppHead :title="data.title" />
  <div class="container page-container">
    <PageTitle :title="data.title" />

    <div class="row">
      <div class="col-md-3">
        <Search
          :data="data"
          :filters="filters.filters"
          :only="['applicants', 'filters']"
        />
      </div>
      <div class="col-md-9">
        <Table
          :options="tableOptions"
          :links="applicants.links"
          :total="applicants.total"
        >
          <template v-slot>
            <tr v-for="(applicant, index) in applicants.data" :key="index">
              <td>
                <Link :href="route('admin.applicants.show', applicant.uuid)">{{
                  applicant.number
                }}</Link>
              </td>
              <td class="">
                {{ localDate(applicant.created_at) }}
              </td>
              <td class="">
                {{ applicant.first_name }} {{ applicant.last_name }}
                <div class="text-muted">{{ applicant.email }}</div>
              </td>
              <td class="text-center">
                {{ applicant.status.name }}
              </td>
            </tr>
          </template>
        </Table>
      </div>
    </div>
  </div>
</template>

<script>
import Search from "./Partials/Search.vue";
import Table from "../../../components/Table.vue";
import DestroyButton from "../../../components/Form/DestroyButton.vue";

export default {
  props: ["data", "applicants", "filters"],
  components: {
    Table,
    DestroyButton,
    Search,
  },
  data() {
    return {
      tableOptions: {
        titles: [
          {
            name: this.__("Number"),
            className: "",
          },
          {
            name: this.__("Date"),
            className: "",
          },
          {
            name: this.__("Name"),
            className: "",
          },
          {
            name: this.__("Status"),
            className: "text-center",
          },
        ],
      },
    };
  },
};
</script>