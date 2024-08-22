<template>
  <li class="nav-item dropdown">
    <a
      v-on:click.prevent="showNotifications()"
      class="nav-link dropdown-toggle caret-off text-white"
      href="#"
      id="navbarDropdown"
      data-bs-toggle="dropdown"
      aria-expanded="false"
    >
      <span
        v-if="numberOfNotification > 0"
        class="badge badge-pill bg-danger rounded-circle"
        style="float: right; margin-top: -8px; margin-left: -5px"
      >
        {{ numberOfNotification }}
      </span>
      <i class="fa fa-bell"></i>
    </a>
    <div
      class="dropdown-menu dropdown-menu-end"
      aria-labelledby="dropdown01"
      style="
        width: 300px;
        font-size: 13px;
        max-height: 300px;
        overflow-y: scroll;
      "
    >
      <div class="text-center" v-if="loadingEnabled">{{ __("Loading") }}</div>
      <div
        class="border-bottom"
        v-if="notifications.length > 0 && !loadingEnabled"
      >
        <button @click="markAllAsRead" type="button" class="btn btn-link">
          {{ __("Mark all as read") }}
        </button>
      </div>

      <Link
        v-if="!loadingEnabled"
        v-for="(notification, index) in notifications"
        :key="index"
        class="dropdown-item"
        style="
          white-space: normal !important;
          padding-top: 10px;
          padding-bottom: 10px;
          border-bottom: 1px solid #eee;
        "
        :href="notification.url"
        >
        <div>{{ notification.message }}</div>
        <small class="form-text text-muted">{{ notification.moment }}</small>
      </Link>
      <div
        v-if="notifications.length == 0 && !loadingEnabled"
        class="text-center"
      >
        {{ __("No unread notification") }}
      </div>
      <Link
        v-if="!loadingEnabled"
        class="dropdown-item btn btn-light text-center"
        style="
          white-space: normal !important;
          padding-top: 10px;
          padding-bottom: 10px;
          border-bottom: 1px solid #eee;
        "
        :href="route('notifications_index')"
        >{{ __("View all notifications") }}</Link
      >
    </div>
  </li>
</template>

<script>
export default {
  data() {
    return {
      url_push_notification: route("get_push_notification_count"),
      url_get_notifications: route("get_unread_notifications"),    
      notifications: [],
      loadingEnabled: false,
      numberOfNotification: 0,
    };
  },

  mounted() {
    if(this.$page.props.enable_instant_notification)
    {
      this.checkPushNotification();
      setInterval(this.checkPushNotification, this.$page.props.instant_notification_check_interval);
    }
  },
  methods: {
    markAllAsRead() {
      var $scope = this;
      axios.post(route("notification_all_mark_as_read")).then(function () {
        $scope.numberOfNotification = [];
      });
    },
    checkPushNotification() {
      var $scope = this;
      axios
        .get(this.url_push_notification)
        .then(function (response) {
          $scope.numberOfNotification = response.data;
        })
        .catch(function (error) {});
    },

    showNotifications: function () {
      this.numberOfNotification = 0;
      this.loadingEnabled = true;
      var $scope = this;
      axios
        .get(this.url_get_notifications)
        .then(function (response) {
          $scope.notifications = response.data;
          $scope.loadingEnabled = false;
        })
        .catch(function (error) {
          $scope.loadingEnabled = false;
        });
    },
  },
};
</script>

<style>
.caret-off::before {
    display: none !important;
}
.caret-off::after {
    display: none !important;
}
</style>