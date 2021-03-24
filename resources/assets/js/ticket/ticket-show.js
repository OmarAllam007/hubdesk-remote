import Vue from 'vue';
import TicketShow from "./show/TicketShow";
import Notifications from 'vue-notification';

Vue.use(Notifications);
import VueSweetalert2 from 'vue-sweetalert2';
// If you don't need the styles, do not connect
import 'sweetalert2/dist/sweetalert2.min.css';
import axios from 'axios';
Vue.use(VueSweetalert2)

import translate from '../translation';
import translation from "../translation";
import _ from 'lodash';
import Session from "../helpers/Session";
// Vue.prototype.$translate = this.t;
window.axios = axios;
window.app = new Vue({
    el: '#ticketShow',
    data() {
        return {
            translations: [],
            loading: false
        }
    },
    beforeCreate() {
    },
    methods: {
        t(word) {
            let translation = _.find(this.translations, {'word': word});

            if (translation) {
                return translation.translation;
            }

            return word;
        }
    },
    components: {TicketShow}
});