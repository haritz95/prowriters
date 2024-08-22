<template>
  <AppHead :title="data.title" />
  <div class="container page-container">
    <PageTitle :title="data.title">
      <Link class="btn btn-sm btn-light me-2" :href="route('admin.calendar')"> <i class="fa-regular fa-calendar"></i> {{ __("Calendar") }}</Link>

      <button @click="toggleStatistics" type="button" class="btn btn-sm btn-light me-2">
        <i class="fa-solid fa-chart-column"></i>&nbsp
        <span v-if="showStatistics">{{ __("Hide") }}</span>
        <span v-else>{{ __("Show") }}</span>
        &nbsp<span>{{ __("Statistics") }}</span>
      </button>

      <AddButton :href="route('admin.tasks.chooseService')" />
    </PageTitle>

    <div v-if="showStatistics">
      <div class="row mb-4" v-for="(statistics, index) in data.statistics" :key="index">
        <div
          class="col-md-2 col-sm-6"
          v-for="(statistic, statIndex) in statistics"
          :key="statIndex"
        >
          <div class="shadow bg-white rounded text-center p-1">
            <div class="mt-2">{{ statistic.value }}</div>
            <span style="font-size: 12px" :style="{ color: statistic.color }">{{
              statistic.name
            }}</span>
          </div>
        </div>
      </div>
    </div>
    <div class="row mt-4">
      <div class="col-md-3">
        <Search :data="data" :filters="filters.filters" :only="['tasks', 'filters']" />
      </div>
      <div class="col-md-9">
        <Table
          :options="tableOptions"
          :links="tasks.links"
          :total="tasks.total"
          tableStyle="table-striped table-hover"
        >
          <template v-slot>
            <tr
              class="mb-2 border-bottom"
              v-for="(task, index) in tasks.data"
              :key="index"
            >
              <td colspan="5" class="border">
                <table class="w-100">
                  <tr>
                    <td class="col-md-3">
                      <Link :href="route('admin.tasks.show', task.uuid)">{{
                        task.number
                      }}</Link>
                      <div>
                        <small class="text-muted fw-bold">{{ task.service.name }}</small>
                      </div>
                      <div v-if="task.service_level">
                        <small class="text-muted"
                          >{{ __("Service Level") }} :
                          <span class="text-danger">{{
                            task.service_level.name
                          }}</span></small
                        >
                      </div>
                      <div>
                        <small
                          >{{ __("Customer") }} :
                          <Link
                            :href="route('admin.customers.show', task.customer.uuid)"
                            >{{ task.customer.full_name }}</Link
                          ></small
                        >
                      </div>
                      <span class="badge bg-danger" v-if="!task.invoice_id">{{
                        __("Not Invoiced")
                      }}</span>
                    </td>
                    <td class="col-md-2">
                      <span
                        class="badge rounded-pill"
                        :style="{
                          backgroundColor: task.status.bg_color,
                          color: task.status.color,
                        }"
                        >{{ task.status.name }}</span
                      >
                    </td>
                    <td class="col-md-2">
                      {{ localDate(task.dead_line) }}
                    </td>
                    <td class="col-md-3">
                      <Link
                        v-if="task.author"
                        :href="route('admin.authors.show', task.author.uuid)"
                        >{{ task.author.full_name }}</Link
                      >
                      <span v-else>{{ __("Not assigned") }}</span>
                      <div>
                        <small class="text-muted"
                          >{{ __("Level") }} :
                          <span v-if="task.author_level">{{
                            task.author_level.name
                          }}</span>
                          <span v-else>{{ __("N/A") }}</span>
                        </small>
                      </div>
                    </td>
                    <td class="col-md-2 text-end">
                      {{ formatMoney(task.total) }}
                    </td>
                  </tr>
                  <tr v-if="task.title" class="mt-2 mb-2">
                    <td colspan="4">
                      <div class="task-list-title mt-3 mb-2">
                        {{ __("Title") }} : {{ task.title }}
                      </div>
                    </td>
                  </tr>
                </table>
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

export default {
  components: {
    Search,
    Table,
  },
  props: ["data", "tasks", "filters"],
  data() {
    return {
      showStatistics: false,
      tableOptions: {
        titles: [
          {
            name: this.__("Details"),
            className: "col-md-3",
          },
          {
            name: this.__("Status"),
            className: "col-md-2",
          },
          {
            name: this.__("Deadline"),
            className: "col-md-2",
          },
          {
            name: this.__("Author"),
            className: "col-md-3",
          },
          {
            name: this.__("Total"),
            className: "col-md-2 text-end",
          },
        ],
      },
    };
  },
  methods: {
    toggleStatistics() {
      this.showStatistics = !this.showStatistics;
    },
  },
};
</script>
