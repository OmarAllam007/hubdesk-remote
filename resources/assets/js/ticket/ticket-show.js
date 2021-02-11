import Vue from 'vue';
import TicketShow from "./show/TicketShow";
import Notifications from 'vue-notification';
Vue.use(Notifications);
import VueSweetalert2 from 'vue-sweetalert2';

// If you don't need the styles, do not connect
import 'sweetalert2/dist/sweetalert2.min.css';

Vue.use(VueSweetalert2)

window.app = new Vue({
    el: '#ticketShow',
    components: {TicketShow}
});