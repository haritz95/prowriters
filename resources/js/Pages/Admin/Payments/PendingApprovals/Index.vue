<template>
  <AppHead :title="data.title" />
  <div class="container page-container">
    <PageTitle :title="data.title">
      <SearchBar
        :hide_inactive_search="true"
        :url="data.urls.search"
        :filters="filters.filters"
      />
    </PageTitle>
    <div class="row">
      <div class="col-md-12">
        <small class="text-danger">*** {{ __('Approved payment amount goes to the customer wallet') }}</small>
        <Table
          :options="tableOptions"
          :links="payments.links"
          :total="payments.total"
          :tableStyle="'fs-8'"
        >
          <template v-slot>
            <tr v-for="(payment, index) in payments.data" :key="index">
              <td>
                <Link
                  :href="
                    route('admin.payments.pendingApprovals.show', payment.uuid)
                  "
                  >{{ payment.number }}</Link
                >
              </td>
              <td>{{ localDate(payment.created_at) }}</td>
              <td>
                <Link href="">{{ payment.from.full_name }}</Link>
              </td>
              <td>
                {{ payment.method.name }}
                <div class="text-muted" v-if="payment.reference">
                  {{ __("Reference") }} : {{ payment.reference }}
                </div>
              </td>

              <td class="text-end">{{ formatMoney(payment.amount) }}</td>

              <td class="text-end">
                <button
                  class="btn btn-sm btn-success me-2"
                  @click="approve(payment.uuid)"
                >
                  <i class="far fa-thumbs-up"></i>
                </button>
                <button
                  class="btn btn-sm btn-danger"
                  @click="disapprove(payment.uuid)"
                >
                  <i class="far fa-thumbs-down"></i>
                </button>
              </td>
            </tr>
          </template>
        </Table>
      </div>
    </div>
  </div>
</template>

<script>
import Table from "../../../../components/Table.vue";
import SearchBar from "../../../../Shared/SearchBar.vue";

export default {
  components: {
    Table,
    SearchBar,
  },
  props: ["data", "payments", "filters"],
  methods: {
    approve(uuid) {
      let url = route("admin.payments.pendingApprovals.approve", uuid);
      let scope = this;
      this.confirmDialog(scope.__("Yes, Approve"), () => {
        scope.$inertia.get(url, scope.inertiaConfig);
      });
    },
    disapprove(uuid) {
      let scope = this;
      let url = route("admin.payments.pendingApprovals.disapprove", uuid);
      this.confirmDialog(scope.__("Yes, Disapprove"), () => {
        scope.$inertia.get(url, scope.inertiaConfig);
      });
    },
  },
  data() {
    return {
      inertiaConfig: { preserveScroll: false },
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
            name: this.__("From"),
            className: "",
          },
          {
            name: this.__("Method"),
            className: "",
          },
          {
            name: this.__("Amount"),
            className: "text-end",
          },
          {
            name: this.__("Action"),
            className: "text-end",
          },
        ],
      },
    };
  },
};
</script>