<template>
  <AppHead :title="data.title" />
  <div class="container page-container">
    <PageTitle :title="data.title" />
    <div class="row">
      <div class="col-md-3">
        <Search :data="data" :filters="filters.filters" :only="['tasks', 'filters']" />
      </div>
      <div class="col-md-9">
        <div class="data-container">
          <Table
            :options="tableOptions"
            :links="tasks.links"
            :total="tasks.total"
            :tableStyle="'table-striped table-hover'"
          >
            <template v-slot>
              <tr class="mb-2 border-bottom" v-for="(task, index) in tasks.data" :key="index">
                <td colspan="4" class="border">
                  <table class="w-100">
                    <tr>
                      <td class="col-md-5">
                        <Link :href="route('author.tasks.show', task.uuid)">{{
                          task.number
                        }}</Link>
                        <div>{{ task.service.name }}</div>
                        <span
                          class="badge text-bg-dark"
                          v-if="task.is_archived_for_author"
                          >{{ __("Archived") }}</span
                        >
                      </td>
                      <td class="col-md-3">
                        {{ localDate(task.dead_line_for_author) }}
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
                      <td class="col-md-2 text-end">
                        {{ formatMoney(task.author_payment_amount) }}
                      </td>
                    </tr>
                    <tr v-if="task.title" class="mt-2 mb-2">
                      <td colspan="4">
                        <div class="task-list-title mt-3 mb-2">{{ __("Title") }} : {{ task.title }}</div>
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
  </div>
</template>

<script>
import Table from "../../../components/Table.vue";
import Search from "./Partials/Search.vue";

export default {
  components: {
    Table,
    Search,
  },
  props: ["data", "tasks", "filters"],
  data() {
    return {
      tableOptions: {
        titles: [
          {
            name: this.__("Details"),
            className: "col-md-5",
          },
          {
            name: this.__("Deadline"),
            className: "col-md-3",
          },
          {
            name: this.__("Status"),
            className: "col-md-2",
          },
          {
            name: this.__("Earnings"),
            className: "col-md-2 text-end",
          },
        ],
      },
    };
  },
};
</script>
