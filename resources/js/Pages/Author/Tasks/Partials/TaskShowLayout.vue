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
            <Countdown :until="task.dead_line_for_author" />
          </div>
          <div class="d-flex justify-content-between mt-4">
            <h4 class="text-white">{{ task.number }}</h4>
            <div>
              <span class="text-white">{{ __("Status") }} : </span>
              <span
                class="badge"
                :style="{
                  color: task.status.color,
                  'background-color': task.status.bg_color,
                }"
                >{{ task.status.name }}</span
              >
            </div>
          </div>

          <!-- Account navigation -->
          <div class="d-flex mt-4 fs-8">
            <ul class="nav nav-tabs task-navigation" id="myTab" role="tablist">
              <li class="nav-item">
                <Link
                  class="nav-link"
                  :class="{ active: isActiveTab('general') }"
                  :href="route('author.tasks.show', task.uuid)"
                  aria-selected="true"
                  >{{ __("General") }}</Link
                >
              </li>
              <li class="nav-item">
                <Link
                  class="nav-link"
                  :class="{ active: isActiveTab('attachment') }"
                  :href="route('author.tasks.attachments.index', task.uuid)"
                  >{{ __("Attachments") }}</Link
                >
              </li>
              <li class="nav-item">
                <Link
                  class="nav-link"
                  :class="{ active: isActiveTab('discussions') }"
                  :href="route('author.tasks.discussions.index', task.uuid)"
                  >{{ __("Discussions") }}</Link
                >
              </li>
              <li class="nav-item">
                <Link
                  class="nav-link"
                  :class="{ active: isActiveTab('internal-discussions') }"
                  :href="
                    route(
                      'author.tasks.internal-discussions.index',
                      task.uuid
                    )
                  "
                  >{{ __("Internal Discussions") }}</Link
                >
              </li>
              <li class="nav-item">
                <Link
                  class="nav-link"
                  :class="{ active: isActiveTab('content') }"
                  :href="route('author.tasks.content', task.uuid)"
                  >{{ __("Content Preview") }}</Link
                >
              </li>
              <li class="nav-item">
                <Link
                  class="nav-link"
                  :class="{ active: isActiveTab('works') }"
                  :href="route('author.tasks.works.index', task.uuid)"
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
  components: {
    Countdown,
  },
  props: ["task", "activeTab"],
  methods: {
    isActiveTab(tab) {
      return this.activeTab == tab ? true : false;
    },
    showCountDown(task) {
      let statuses =
        this.$page.props.auth_user.hide_countdown_timer_for_task_statuses;

      if (Array.isArray(statuses) && statuses.includes(task.task_status_id)) {
        return false;
      }
      return true;
    },
  },
};
</script>