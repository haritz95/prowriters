<template>
  <AppHead :title="data.title" />
  <div class="container page-container">
    <PageTitle :title="data.title">
      <Link :href="route('customer.tasks.create')" class="btn btn-primary btn-sm"
        ><i class="fa-solid fa-plus"></i> {{ __("New Task") }}</Link
      >
    </PageTitle>

    <div class="row">
      <div class="col-md-3">
        <div class="sticky-top">
          <SearchForm :data="data" :filters="filters" />
        </div>
      </div>
      <div class="col-md-9">
        <div class="data-container">
          <Table
            :options="tableOptions"
            :links="tasks.links"
            :total="tasks.total"
            tableStyle="table-striped table-hover"
          >
            <template v-slot>
              <tr class="mb-2 border-bottom" v-for="(task, index) in tasks.data" :key="index">
                <td colspan="4" class="border">
                  <table class="w-100">
                    <tr>
                      <td class="col-md-4">
                        <Link :href="route('customer.tasks.show', task.uuid)">{{
                          task.number
                        }}</Link>
                        <div>
                          <small class="text-muted">{{ task.service.name }}</small>
                        </div>
                      </td>
                      <td class="col-md-3">
                        <span
                          class="badge rounded-pill"
                          :style="{
                            backgroundColor: task.status.bg_color,
                            color: task.status.color,
                          }"
                          >{{ task.status.name }}</span
                        >
                      </td>
                      <td class="col-md-3">
                        {{ localDate(task.dead_line) }}
                      </td>
                      <td class="col-md-2 text-end">
                        {{ formatMoney(task.total) }}
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
import SearchForm from "./Partials/SearchForm.vue";
import Table from "../../../components/Table.vue";

export default {
  components: {
    SearchForm,
    Table,
  },
  props: ["data", "tasks", "filters"],
  data() {
    return {
      tableOptions: {
        titles: [
          {
            name: this.__("Details"),
            className: "col-md-4",
          },
          {
            name: this.__("Status"),
            className: "col-md-3",
          },
          {
            name: this.__("Deadline"),
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
};
</script>
