<template>
  <AppHead :title="data.title" />
  <div class="container page-container">
    <PageTitle :title="data.title">
      <Link
        v-if="$page.props.is_bidding_enable"
        :href="route('customer.bidRequests.services')"
        class="btn btn-sm btn-outline-primary me-2"
      >
        <i class="fa-solid fa-plus"></i> {{ __("New Bid Request") }}</Link
      >

      <Link
        v-if="$page.props.is_ordering_enable"
        :href="route('customer.tasks.create')"
        class="btn btn-sm btn-outline-primary me-2"
      >
        <i class="fa-solid fa-plus"></i> {{ __("New Task") }}</Link
      >

      <Link
        :href="route('customer.transactions.funds.create')"
        class="btn btn-sm btn-outline-success"
      >
        <i class="fa-solid fa-wallet"></i> {{ __("Add Funds") }}</Link
      >
    </PageTitle>

    <div class="row mt-2">
      <div
        class="col-md-4 mb-2 mb-md-0"
        v-for="(statistic, index) in statistics"
        :key="index"
      >
        <div class="d-flex bg-light p-3">
          <div class="flex-shrink-0 align-middle statistics-box">
            <div :class="statistic.bg_color">
              <i class="align-middle" :class="statistic.icon"></i>
            </div>
          </div>
          <div class="flex-grow-1 ms-2">
            <div class="text-uppercase fs-9 lh-sm">
              {{ statistic.title }}
            </div>
            <div class="fs-5 lh-1 fw-bold">
              <i class="fas fa-spinner fa-spin" v-if="!statistic.data"></i>
              <span v-else>
                <span v-if="statistics.is_money">
                  {{ formatMoney(statistic.data.value) }}</span
                >
                <span v-else v-text="statistic.data.value"></span>
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row mt-4">
      <div class="col-md-12">
        <fieldset class="border rounded-3 p-3 mb-4">
          <legend class="float-none w-auto px-3 fs-8">
            {{ __("Tasks waiting for your review") }}
          </legend>

          <div class="data-container">
            <Table :options="tableOptions" :tableStyle="'alert table-striped'">
              <template v-slot>
                <tr class="mb-2" v-for="(task, index) in data.tasks" :key="index">
                  <td class="col-md-6">
                    <Link :href="route('customer.tasks.show', task.uuid)">{{
                      task.number
                    }}</Link>
                    <div>
                      <small class="text-muted">{{ task.service.name }}</small>
                    </div>
                    <div class="task-list-title mt-3 mb-2">
                      {{ __("Title") }} : {{ task.title }}
                    </div>
                  </td>

                  <td class="col-md-3">
                    {{ localDate(task.dead_line) }}
                  </td>
                  <td class="col-md-3 text-end">
                    {{ formatMoney(task.total) }}
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
          bg_color: "bg-purple",
          data: null,
          icon : 'fas fa-tasks',
        },        
        {
          name: "tasks_for_review",
          title: this.__("Tasks for your review"),
          bg_color: "bg-red",
          data: null,
          icon : 'fas fa-exclamation-circle',
        },
        {
          name: "wallet_balance",
          title: this.__("Wallet Balance"),
          bg_color: "bg-blue",
          data: null,
          icon : 'fas fa-file-invoice-dollar',
          is_money: true,
        },
      ],
      tableOptions: {
        titles: [
          {
            name: this.__("Details"),
            className: "col-md-6",
          },
          {
            name: this.__("Deadline"),
            className: "col-md-3",
          },
          {
            name: this.__("Total"),
            className: "col-md-3 text-end",
          },
        ],
      },
    };
  },
  methods: {
    getStatistics() {
      let scope = this;
      axios.post(route("customer.dashboard.statistics")).then(function (response) {
        for (let index = 0; index < scope.statistics.length; index++) {
          const statistic_name = scope.statistics[index].name;
          scope.statistics[index].data = response.data[statistic_name];
        }
      });
    },
  },
};
</script>
