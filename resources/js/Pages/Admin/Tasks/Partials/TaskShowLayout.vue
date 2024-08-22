<template>
  <AppHead :title="task.number" />
  <section
    class="header-account-page d-flex align-items-end navbar-background pt-80"
    data-offset-top="#header-main"
  >
    <div class="container pt-4 pt-lg-0">
      <div class="row mt-3">
        <div class="col-lg-12">
          <div class="text-end text-white" v-if="showCountDown(task)">
            <Countdown :until="task.dead_line" />
          </div>
          <div class="d-flex justify-content-between mt-4">
            <div>
              <h4 class="text-white">
                {{ task.number }}
              </h4>
            </div>
            <div>
              <span class="text-white">{{ __("Status") }} : </span>
              <span
                class="badge me-2"
                :style="{
                  color: task.status.color,
                  'background-color': task.status.bg_color,
                }"
                >{{ task.status.name }}</span
              >
              <span class="badge bg-danger" v-if="!task.invoice_id">{{
                __("Not Invoiced")
              }}</span>
              <span class="badge bg-success" v-else>{{ __("Invoiced") }}</span>
            </div>
          </div>

          <!-- Account navigation -->
          <div class="d-flex mt-4 fs-8">
            <ul class="nav nav-tabs task-navigation" id="myTab" role="tablist">
              <li class="nav-item">
                <Link
                  class="nav-link"
                  :class="{ active: isActiveTab('general') }"
                  :href="route('admin.tasks.show', task.uuid)"
                  aria-selected="true"
                  >{{ __("General") }}</Link
                >
              </li>
              <li class="nav-item">
                <Link
                  class="nav-link"
                  :class="{ active: isActiveTab('content') }"
                  :href="route('admin.tasks.content', task.uuid)"
                  >{{ __("Content") }}</Link
                >
              </li>
              <li class="nav-item">
                <Link
                  class="nav-link"
                  :class="{ active: isActiveTab('attachment') }"
                  :href="route('admin.tasks.attachments.index', task.uuid)"
                  >{{ __("Attachments") }}</Link
                >
              </li>
              <li class="nav-item">
                <Link
                  class="nav-link"
                  :class="{ active: isActiveTab('discussions') }"
                  :href="route('admin.tasks.discussions.index', task.uuid)"
                  >{{ __("Discussions") }}</Link
                >
              </li>
              <li class="nav-item">
                <Link
                  class="nav-link"
                  :class="{ active: isActiveTab('internal-discussions') }"
                  :href="route('admin.tasks.internal-discussions.index', task.uuid)"
                  >{{ __("Internal Discussions") }}</Link
                >
              </li>
              <li class="nav-item" v-if="hasRole(ADMIN_ROLES.SUPER_ADMIN)">
                <Link
                  class="nav-link"
                  :class="{ active: isActiveTab('financial') }"
                  :href="route('admin.tasks.financial.index', task.uuid)"
                  >{{ __("Financial") }}</Link
                >
              </li>
              <li class="nav-item" v-if="$page.props.is_quality_control_enable">
                <Link
                  class="nav-link"
                  :class="{ active: isActiveTab('qa') }"
                  :href="route('admin.tasks.qa.index', task.uuid)"
                  >{{ __("Quality Assurance") }}</Link
                >
              </li>
              <li class="nav-item">
                <Link
                  class="nav-link"
                  :class="{ active: isActiveTab('works') }"
                  :href="route('admin.tasks.works.index', task.uuid)"
                  >{{ __("Submitted Works") }}</Link
                >
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>

  <div class="container page-container mt-3">
    <div class="row">
      <div class="col-md-12">
        <slot />
      </div>
    </div>
  </div>
</template>

<script>
import Countdown from "../../../../components/Countdown.vue";
export default {
  props: ["task", "activeTab"],
  components: {
    Countdown,
  },
  methods: {
    isActiveTab(tab) {
      return this.activeTab == tab ? true : false;
    },
    showCountDown(task) {
      let statuses = this.$page.props.auth_user.hide_countdown_timer_for_task_statuses;

      if (Array.isArray(statuses) && statuses.includes(task.task_status_id)) {
        return false;
      }
      return true;
    },
  },
};
</script>
