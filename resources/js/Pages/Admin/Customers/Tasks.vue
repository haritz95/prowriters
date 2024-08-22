<template>
  <ProfileLayout :customer="customer">
    <h5 class="mt-4 mb-4">{{ data.title }}</h5>    
    <div class="data-container">
      <Table :options="tableOptions" :links="tasks.links" :total="tasks.total">
        <template v-slot>
          <tr class="mb-2" v-for="(task, index) in tasks.data" :key="index">
            <td class="col-md-2">
              <Link :href="route('admin.tasks.show', task.uuid)">{{ task.number }}</Link>
              <div>
                <small class="text-muted">{{ task.service.name }}</small>
              </div>
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
              {{ formatMoney(task.total) }}
            </td>           
          </tr>
        </template>
      </Table>
    </div>
  </ProfileLayout>
</template>

<script>
import Table from "../../../components/Table.vue";
import ProfileLayout from "./Partials/ProfileLayout.vue";

export default {
  components: {
    Table,
    ProfileLayout,
  },
  props: ["data", "tasks", "customer"],
  data() {
    return {
      tableOptions: {
        titles: [
          {
            name: this.__("Number"),
            className: "",
          },
          {
            name: this.__("Status"),
            className: "",
          },
          {
            name: this.__("Total"),
            className: "text-end",
          },          
        ],
      },
    };
  },
};
</script>