<template>
  <FullCalendar :options="calendarOptions" />
</template>

  <script>
import FullCalendar from "@fullcalendar/vue3";
import listPlugin from "@fullcalendar/list";
// import { Tooltip } from "bootstrap";

export default {
  components: {
    FullCalendar, // make the <FullCalendar> tag available
  },
  props: ["events", "user_type"],
  data() {
    return {
      calendarOptions: {
        plugins: [listPlugin],
        initialView: "listWeek",
        headerToolbar: {
          left: "title",
          center: "",
          right: "listDay,listWeek,listMonth",
        },  
        views: {
          listDay: { buttonText: this.__('Today')},
          listWeek: { buttonText: this.__('This Week') },
          listMonth: { buttonText: this.__('This Month') },
        },
        events: this.events,
        // eventDidMount: function (info) {
        //   var tooltip = new Tooltip(info.el, {
        //     title: info.event.extendedProps.description,
        //     placement: "top",
        //     trigger: "hover",
        //     container: "body",
        //   });
        // },
        eventClick: this.handleClick,
      },
    };
  },
  methods: {
    handleClick(arg) {
      let name = (this.user_type == 'admin') ? "admin.tasks.show" : "author.tasks.show";    
      this.$inertia.get(
        route(name, arg.event.extendedProps.uuid)
      );
    },
  },
};
</script>
<style>
.fc-event {
  cursor: pointer;
}

/* :root {
    --fc-button-bg-color: green;
    --fc-button-border-color
    --fc-button-active-bg-color
    --fc-button-active-border-color
} */
</style>