<template>
    <AppHead :title="data.title" />
    <div class="container page-container">
      <PageTitle :title="data.title" />
      <div class="row">
        <!-- <div class="col-md-3">
          <Search
            :data="data"         
            :filters="filters.filters"
            :only="['tasks', 'filters']"
          />
        </div> -->
        <div class="col-md-12">
          <div class="data-container">
            <Table
              :options="tableOptions"
              :links="tasks.links"
              :total="tasks.total"
              :tableStyle="'table-striped'"
            >
              <template v-slot>
                <tr
                  class="mb-2"
                  v-for="(task, index) in tasks.data"
                  :key="index"
                >
                  <td class="col-md-5">
                    <Link :href="route('author.find.work.show', task.uuid)">{{ task.number }}</Link>                 
                    <div class="taskcard__workType">{{ task.service.name }}</div>                    
                    <small class="text-muted">{{ __('Author Level') }} : {{ task.author_level.name }}</small>                    
                  </td>
                  <td class="col-md-2">
                    {{ localDateTime(task.dead_line_for_author) }}
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
              </template>
            </Table>
          </div>
        </div>
  
    
      </div>
    </div>
  </template>
  
  <script>
  import Table from "../../../../components/Table.vue";
  import Search from "../Partials/Search.vue";
  
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
              className: "col-md-3",
            },
            {
              name: this.__("Deadline"),
              className: "col-md-5",
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
  