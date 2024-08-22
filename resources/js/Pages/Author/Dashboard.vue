<template>
  <AppHead :title="data.title" />
  <div class="container page-container">
    <PageTitle :title="data.title" />

    <div class="row mt-2">
      <div
        class="col-md-3 mb-2 mb-md-0"
        v-for="(statistic, index) in statistics"
        :key="index"
      >
        <div class="d-flex bg-light p-3">
          <div class="flex-shrink-0 align-middle statistics-box">
            <div :style="{ backgroundColor : statistic.bg_color}">
              <i class="align-middle" :class="statistic.icon"></i>
            </div>
          </div>
          <div class="flex-grow-1 ms-2">
            <div class="text-uppercase fs-9 lh-sm">
              {{ statistic.title }}
            </div>
            <div class="fs-6 lh-1 fw-bold mt-2">
              <i class="fas fa-spinner fa-spin" v-if="!statistic.data"></i>
              <span v-else v-text="statistic.data.value"></span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row mt-4">
      <div class="col-md-12 mb-4">
        <fieldset class="border rounded-3 p-3 mb-4 h-100">
          <legend class="float-none w-auto px-3 fs-6">
            {{ __("Announcements") }}
          </legend>

          <Table :options="announcementTableOptions" :tableStyle="'table-striped'">
            <template v-slot>
              <tr v-for="(announcement, index) in data.announcements" :key="index">
                <td>
                  <Link :href="route('author.announcements.show', announcement.uuid)">{{
                    announcement.number
                  }}</Link>
                </td>
                <td>
                  {{ announcement.title }}
                </td>                
              </tr>
            </template>
          </Table>
        </fieldset>
      </div>
      <div class="col-md-6">
        <fieldset class="border rounded-3 p-3 mb-4 h-100">
          <legend class="float-none w-auto px-3 fs-6">
            {{ __("Tasks in progress") }}
          </legend>

          <Table :options="tableOptions" :tableStyle="'table-striped'">
            <template v-slot>
              <tr
                class="mb-2"
                v-for="(task, index) in data.tasks_in_progress"
                :key="index"
              >
                <td colspan="3" class="border">
                  <table class="w-100">
                    <tr>
                      <td class="col-md-3">
                        <Link :href="route('author.tasks.show', task.uuid)">{{
                          task.number
                        }}</Link>
                        <div>
                          <small class="text-muted">{{ task.service.name }}</small>
                        </div>
                      </td>
                      <td class="col-md-2">
                        {{ localDate(task.dead_line_for_author) }}
                      </td>
                      <td class="col-md-2 text-end">
                        {{ formatMoney(task.author_payment_amount) }}
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
        </fieldset>
      </div>

      <div class="col-md-6">
        <fieldset class="border rounded-3 p-3 mb-4 h-100">
          <legend class="float-none w-auto px-3 fs-6">
            {{ __("Tasks requires revision") }}
          </legend>

          <Table :options="tableOptions" :tableStyle="'table-striped'">
            <template v-slot>
              <tr
                class="mb-2"
                v-for="(task, index) in data.tasks_requires_revision"
                :key="index"
              >
                <td colspan="3" class="border">
                  <table class="w-100">
                    <tr>
                      <td class="col-md-3">
                        <Link :href="route('author.tasks.show', task.uuid)">{{
                          task.number
                        }}</Link>
                        <div>
                          <small class="text-muted">{{ task.service.name }}</small>
                        </div>
                      </td>
                      <td class="col-md-2">
                        {{ localDate(task.dead_line_for_author) }}
                      </td>
                      <td class="col-md-2 text-end">
                        {{ formatMoney(task.author_payment_amount) }}
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
        </fieldset>
      </div>
    </div>
  </div>
</template>

<script>
import Table from "../../components/Table.vue";
export default {
  components: {
    Table,
  },
  props: ["data"],
  created() {
    this.getStatistics();
  },
  data() {
    return {
      statistics: [
        {
          name: "tasks_in_progress",
          title: this.__("Tasks in progress"),
          bg_color: "#ffcccc",
          data: null,
          icon : 'fas fa-tasks',
        },
        {
          name: "tasks_require_revision",
          title: this.__("Tasks require revision"),
          bg_color: "#ccffcc",
          data: null,
          icon : 'fas fa-exclamation-circle',
        },
        {
          name: "billable_amount",
          title: this.__("Billable Amount"),
          bg_color: "#ccccff",
          data: null,
          icon : 'fas fa-file-invoice-dollar',
        },
        {
          name: "outstanding_payments",
          title: this.__("Outstanding Payments"),
          bg_color: "#ffffcc",
          data: null,
          icon : 'fas fa-money-check-alt',
        },
    
      ],
      tableOptions: {
        titles: [
          {
            name: this.__("Details"),
            className: "col-md-3",
          },
          {
            name: this.__("Deadline"),
            className: "col-md-2",
          },
          {
            name: this.__("Total"),
            className: "col-md-2 text-end",
          },
        ],
      },
      announcementTableOptions: {
        titles: [
          {
            name: this.__("Number"),
            className: "col-md-2",
          },
          {
            name: this.__("Title"),
            className: "col-md-8",
          },          
        ],
      },
    };
  },
  methods: {
    getStatistics() {
      let scope = this;
      axios.post(route("author.dashboard.statistics")).then(function (response) {
        for (let index = 0; index < scope.statistics.length; index++) {
          const statistic_name = scope.statistics[index].name;
          scope.statistics[index].data = response.data[statistic_name];
        }
      });
    },
  },
};
</script>
