<template>
  <AppHead :title="data.title" />
  <div class="container page-container">
    <PageTitle :title="data.title" />
    <div class="row">
      <div class="col-md-12">
        <Table
          :options="tableOptions"
          :links="notifications.links"
          :total="notifications.total"
          :tableStyle="'fs-8'"
        >
          <template v-slot>
            <tr v-for="(notification, index) in notifications.data" :key="index">
              <td>
                <Link :href="notification.data.url">{{ notification.data.message }}</Link>
              </td>
              <td class="text-end">
                {{ localDateTime(notification.created_at) }}
              </td>
            </tr>
          </template>
        </Table>
      </div>
    </div>
  </div>
</template>

<script>
import Table from "../components/Table.vue";

export default {
  components: {
    Table,
  },
  props: ["data", "notifications"],
  data() {
    return {
      tableOptions: {
        titles: [
          {
            name: this.__("Description"),
            className: "",
          },
          {
            name: this.__("Time"),
            className: "text-end",
          },
        ],
      },
    };
  },
};
</script>
