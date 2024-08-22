<template>
  <TaskShowLayout :task="task" activeTab="general">
    <div class="d-flex justify-content-between flex-sm-row flex-column mb-5">
      <div>
        <Link class="btn btn-sm btn-light" :href="route('admin.tasks.index')">
          <i class="fa-solid fa-arrow-left-long"></i> {{ __("Back to Tasks") }}
        </Link>
        <Link
          v-if="data.is_a_follower"
          class="btn btn-sm btn-primary ms-2"
          :href="route('admin.tasks.unFollow', task.uuid)"
        >
          <i class="fa-solid fa-bell"></i> {{ __("Unfollow") }}
        </Link>
        <Link
          v-else
          class="btn btn-sm btn-outline-primary ms-2"
          :href="route('admin.tasks.follow', task.uuid)"
        >
          <i class="fa-solid fa-bell"></i> {{ __("Follow") }}
        </Link>
      </div>

      <div class="d-flex flex-row-reverse bd-highlight mb-2">
        <DialogLink
          class="btn btn-sm btn-outline-success me-2"
          :href="route('admin.tasks.edit.dates', task.uuid)"
        >
          <i class="fa-regular fa-clock"></i> {{ __("Change Deadline") }}
        </DialogLink>

        <DialogLink
          :href="route('admin.tasks.edit.assignee', task.uuid)"
          class="btn btn-sm btn-outline-success me-2"
        >
          <i class="fa-solid fa-thumbtack"></i> {{ __("Assign Author") }}
        </DialogLink>
        <DialogLink
          :href="route('admin.tasks.edit.status', task.uuid)"
          class="btn btn-sm btn-outline-success me-2"
        >
          <i class="fa-solid fa-bars-progress"></i> {{ __("Change Status") }}
        </DialogLink>

        <DialogLink
          v-if="$page.props.is_quality_control_enable"
          :href="route('admin.tasks.edit.editor', task.uuid)"
          class="btn btn-sm btn-outline-success me-2"
        >
          <i class="fa-regular fa-pen-to-square"></i> {{ __("Assign Editor") }}
        </DialogLink>

        <button
          v-if="!task.invoice_id"
          @click="destroy"
          class="btn btn-sm btn-outline-danger me-2"
          :href="route('admin.tasks.edit', task.uuid)"
        >
          <i class="fa-solid fa-trash-can"></i> {{ __("Delete") }}
        </button>

        <Link
          v-if="data.allow.archiving"
          class="btn btn-sm btn-outline-secondary me-2"
          :href="route('admin.tasks.archive', task.uuid)"
          method="post"
          as="button"
          type="button"
          preserve-scroll
        >
          <i class="fa-solid fa-box-archive"></i> {{ __("Archive") }}
        </Link>

        <Link
          v-if="data.allow.unarchiving"
          class="btn btn-sm btn-outline-warning me-2"
          :href="route('admin.tasks.unarchive', task.uuid)"
          method="post"
          as="button"
          type="button"
          preserve-scroll
        >
          <i class="fa-solid fa-box-open"></i> {{ __("Unarchive") }}
        </Link>

        <Link
          v-if="!task.invoice_id"
          class="btn btn-sm btn-outline-warning me-2"
          :href="route('admin.tasks.edit', task.uuid)"
        >
          <i class="fa-solid fa-pen-to-square"></i> {{ __("Edit") }}
        </Link>

        <Link
          v-if="!task.invoice_id"
          class="btn btn-sm btn-outline-primary me-2"
          :href="route('admin.invoices.create', { customer_id: task.customer_id })"
        >
          <i class="fa-solid fa-file-invoice"></i> {{ __("Create Invoice") }}
        </Link>
      </div>
    </div>

    <Details :task="task" :data="data" />
  </TaskShowLayout>
</template>

<script>
import TaskShowLayout from "./Partials/TaskShowLayout.vue";
import Details from "./Partials/Details.vue";

export default {
  props: ["task", "data"],
  components: {
    TaskShowLayout,
    Details,
  },
  methods: {
    destroy() {
      this.deleteConfirmDialog(this, route("admin.tasks.destroy", this.task.uuid));
    },
  },
};
</script>
