<template>
  <AppHead :title="data.title" />
  <div class="container page-container">
    <PageTitle :title="data.title">
      <Link
        :href="route('admin.tasks.chooseService')"
        class="btn btn-sm btn-outline-primary me-2"
      >
        <i class="fa-solid fa-plus"></i> {{ __("New Task") }}</Link
      >

      <Link
        :href="route('admin.invoices.create')"
        class="btn btn-sm btn-outline-primary me-2"
      >
        <i class="fa-solid fa-plus"></i> {{ __("New Invoice") }}</Link
      >

      <Link :href="route('admin.payments.create')" class="btn btn-sm btn-outline-primary">
        <i class="fa-solid fa-plus"></i> {{ __("New Payment") }}</Link
      >
    </PageTitle>

    <div class="row">
      <div class="col-md-3">
        <div class="card card-bg-indigo">
          <div class="card-body">
            <h5 class="card-title">{{ __("New Customers") }}</h5>
            <h5 class="float-end">
              <i class="fas fa-spinner fa-spin" v-if="!statistics.customers"></i>
              <span v-else v-text="statistics.customers.value"></span>
            </h5>
            <p>{{ __("Last 7 days") }}</p>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card card-bg-indigo-light">
          <div class="card-body">
            <h5 class="card-title">{{ __("Tasks") }}</h5>
            <h5 class="float-end">
              <i class="fas fa-spinner fa-spin" v-if="!statistics.tasks"></i>
              <span v-else v-text="statistics.tasks.value"></span>
            </h5>
            <p>{{ __("In progress") }}</p>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card card-bg-red-light">
          <div class="card-body">
            <h5 class="card-title">{{ __("Bills from Authors") }}</h5>
            <h5 class="float-end">
              <i
                class="fas fa-spinner fa-spin"
                v-if="!statistics.unpaid_bills_amount"
              ></i>
              <span v-else>{{ formatMoney(statistics.unpaid_bills_amount.value) }}</span>
            </h5>
            <p>{{ __("Unpaid") }}</p>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card card-bg-green">
          <div class="card-body">
            <h5 class="card-title">{{ __("Profit") }}</h5>
            <h5 class="float-end">
              <i class="fas fa-spinner fa-spin" v-if="!statistics.profit_amount"></i>
              <span v-else>{{ formatMoney(statistics.profit_amount.value) }}</span>
            </h5>
            <p>{{ __("Last 30 days") }}</p>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-8">
        <Line v-if="income_graph_records" :records="income_graph_records" />
        <div v-else class="text-center">{{ __("Loading graph ...") }}</div>
      </div>

      <div class="col-md-4">
        <div class="dashboard-stat hoki">
          <div class="visual">
            <i class="fa-solid fa-money-bill-wave"></i>
          </div>
          <div class="details">
            <div class="number">
              <i class="fas fa-spinner fa-spin" v-if="!statistics.uninvoiced_tasks"></i>
              <span v-else v-text="statistics.uninvoiced_tasks.value"></span>
            </div>
            <div class="desc">{{ __("Uninvoiced Tasks") }}</div>
          </div>
        </div>

        <!-- <div v-for="(activity, index) in data.activities" :key="index">
          <div>
            <Link :href="activity.causer_url">{{ activity.causer }}</Link>
            {{ activity.description }}
            <Link :href="activity.subject_url">{{ activity.subject }}</Link>
          </div>
          <div>
            <small class="text-muted">{{ localDateTime(activity.time) }}</small>
          </div>
        </div> -->
      </div>
    </div>
  </div>
</template>

<script>
import Line from "../../components/Charts/Line.vue";

export default {
  props: ["data"],
  components: {
    Line,
  },
  created() {
    this.getStatistics("customers");
    this.getStatistics("tasks");
    this.getStatistics("unpaid_bills_amount");
    this.getStatistics("profit_amount");
    this.getStatistics("uninvoiced_tasks");
    this.loadIncomeGraph();
  },
  data() {
    return {
      income_graph_records: null,
      statistics: {
        customers: null,
        tasks: null,
        paid_bills_amount: null,
        profit_amount: null,
        uninvoiced_tasks: null,
      },
      // greet: [
      //   "What are you doing that early?",
      //   "Good Morning",
      //   "Good Afternoon",
      //   "Good Evening",
      // ][parseInt((new Date().getHours() / 24) * 4)],
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
    loadIncomeGraph() {
      let scope = this;

      axios.post(route("admin.dashboard.graph.income")).then(function (response) {
        scope.income_graph_records = {
          labels: response.data.labels,
          data: response.data.values,
        };
      });
    },
  },
};
</script>
