import Vue from 'vue';
import NOTIFICATIONS from "./NOTIFICATIONS.vue";


window.app = new Vue({
    el: '#Notifications',
    components: {'notifications':NOTIFICATIONS}
})
