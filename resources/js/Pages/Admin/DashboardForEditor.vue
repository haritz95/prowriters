<template>
  <AppHead :title="data.title" />
  <div class="container page-container">
    <div class="row">
      <div class="col-md-12">
        <h4>{{ __("Dashboard") }}</h4>
        <hr />
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        <div class="card card-bg-indigo">
          <div class="card-body">
            <h5 class="float-end">
              <i class="fas fa-spinner fa-spin" v-if="!statistics.tasks"></i>
              <span v-else v-text="statistics.tasks.value"></span>
            </h5>
            <p>{{ __("Tasks In progress") }}</p>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card card-bg-red-light">
          <div class="card-body">
            <h5 class="float-end">
              <i
                class="fas fa-spinner fa-spin"
                v-if="!statistics.unassigned_tasks"
              ></i>
              <span v-else v-text="statistics.unassigned_tasks.value"></span>
            </h5>
            <p>{{ __("Unassigned") }}</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card card-bg-green">
          <div class="card-body">
            <h5 class="float-end">
              <i
                class="fas fa-spinner fa-spin"
                v-if="!statistics.submitted_for_qa"
              ></i>
              <span v-else v-text="statistics.submitted_for_qa.value"></span>
            </h5>
            <p>{{ __("Requires Quality Control") }}</p>
          </div>
        </div>
      </div>
    </div>

    <div class="row mt-4">
      <div class="col-md-12">
        <fieldset class="border rounded-3 p-3 mb-4">
          <legend class="float-none w-auto px-3 fs-8">
            {{ __("Tasks assigned to you for Quality Control") }}
          </legend>

          <div class="data-container">
            <Table :options="tableOptions" :tableStyle="'alert table-striped'">
              <template v-slot>
                <tr
                  class="mb-2"
                  v-for="(task, index) in data.tasks"
                  :key="index"
                >
                  <td class="col-md-3">
                    <Link :href="route('admin.tasks.show', task.uuid)">{{
                      task.number
                    }}</Link>
                    <div>
                      <small class="text-muted">{{ task.service.name }}</small>
                    </div>
                  </td>
                  <td class="col-md-2">
                    {{ localDate(task.dead_line) }}
                  </td>
                  <td class="col-md-2 text-end">
                    {{ localDate(task.dead_line_for_author) }}
                  </td>
                </tr>
              </template>
            </Table>
          </div>
        </fieldset>
      </div>
    </div>
  </div>
</template>

<script>
import Table from "../../components/Table.vue";

export default {
  props: ["data"],
  components: {
    Table,
  },
  created() {
    this.getStatistics("tasks");
    this.getStatistics("unassigned_tasks");
    this.getStatistics("submitted_for_qa");
  },
  data() {
    return {
      income_graph_records: null,
      statistics: {
        tasks: null,
        unassigned_tasks: null,
        submitted_for_qa: null,
      },
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
            name: this.__("Deadline for Author"),
            className: "col-md-2 text-end",
          },
        ],
      },
    };
  },
  methods: {
    getStatistics(field) {
      let scope = this;
      axios
        .post(route("admin.dashboard.statistics"), { name: field })
        .then(function (response) {
          scope.statistics[field] = { value: response.data };
        });
    },
  },
};
</script>