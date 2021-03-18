<template>
  <div class="pt-10 ">
    <notifications group="tasks" position="top right" width="40%" classes="notification-alert-style"/>
    <notifications group="replies" position="top right" width="40%" classes="notification-alert-style"/>
    <notifications group="approvals" position="top right" width="40%" classes="notification-alert-style"/>
    <notifications group="notes" position="top right" width="40%" classes="notification-alert-style"/>
  </div>
</template>

<script>
import {EventBus} from "../../EventBus";

export default {
  name: "NotificationsComponent",
  data() {
    return {}
  },
  created() {
    EventBus.$on('send_notification', (group, title, text, type, duration = 4000) => {
      this.$notify({
        group: group,
        title: title,
        text: text,
        type: type,
        duration: duration
      });
    })
  }

}
</script>

<style scoped>

</style>