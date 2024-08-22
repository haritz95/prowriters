<template></template>
<script>
export default {
  watch: {
    "$page.props.flash": {
      deep: true,
      handler(val) {
        if (val) {
          this.config.title = val.title;
          this.config.type = val.type;
          this.config.description = val.message;
          GrowlNotification.notify(this.config);
          this.$page.props.flash = null;
        }
      },
    },
  },
  data() {
    return {
      config: {
        position: "top-right",
        closeTimeout: 4000,
        zIndex: "2000",
      },
    };
  },
};
</script>