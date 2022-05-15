import Vue from 'vue';
import Notifications from 'vue-notification';

Vue.use(Notifications);

import VueSweetalert2 from 'vue-sweetalert2';
// If you don't need the styles, do not connect
import 'sweetalert2/dist/sweetalert2.min.css';
import axios from 'axios';

Vue.use(VueSweetalert2)

import translation from "../translation";
import _ from 'lodash';
import Session from "../helpers/Session";
// Vue.prototype.$translate = this.t;
window.axios = axios;

import TicketForm from "./TicketForm";

window.app = new Vue({
    el: '#ticketForm',
    data() {
        return {
            translations: [],
            loading: false
        }
    },
    created(){
        // this.loading = true;
        // setTimeout(()=>{
        //     this.translations = translation.load_file('ar');
        //     this.loading = false;
        // }, 2000)

        // this.translations = translation.load_file('ar');
        // console.log('here');
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
    components: {TicketForm}
});