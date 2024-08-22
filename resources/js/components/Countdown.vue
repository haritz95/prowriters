<template>
  <span class="badge bg-info" v-if="expired" v-text="expired_message"></span>
  <span v-else>
    {{ __("Time Remaining") }} :
    <span>{{ remaining.days }}d, </span>
    <span>{{ remaining.hours }}h, </span>
    <span>{{ remaining.minutes }}m, </span>
    <span>{{ remaining.seconds }}s </span>
  </span>
</template>

<script>
import moment from "moment";

export default {
  props: {
    until: String,
  },

  data() {
    return {
      now: new Date(),
      interval: this.refreshEverySecond(),
      expired: false,
      expired_message: this.__("Deadline expired"),
    };
  },

  computed: {
    remaining() {
      if (!this.until) {
        return {
          total: 0,
          days: 0,
          hours: 0,
          minutes: 0,
          seconds: 0,
        };
      }
      let remaining = moment.duration(Date.parse(this.until) - this.now);

      if (remaining <= 0) {
        this.expired = true;
        return false;
      }

      return {
        total: remaining,
        days: remaining.days(),
        hours: remaining.hours(),
        minutes: remaining.minutes(),
        seconds: remaining.seconds(),
      };
    },
  },

  methods: {
    refreshEverySecond() {
      return setInterval(() => {
        this.now = new Date();
      }, 1000);
    },
  },
};
</script>