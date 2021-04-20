import Vue from 'vue';
import ApprovalShow from "./approvals/ApprovalShow";
import NotificationsComponent from "./show/NotificationsComponent";
import Notifications from 'vue-notification';
import _ from "lodash";


Vue.use(Notifications);

window.axios = require('axios');

window.axios.defaults.headers.common = {
    'X-Requested-With': 'XMLHttpRequest',
    'X-CSRF-TOKEN' : document.querySelector('meta[name="csrf-token"]').getAttribute('content')
};

window.app = new Vue({
    el: '#TicketApprovalShow',
    data() {
        return {
            translations: [],
            loading: false
        }
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
    components: { ApprovalShow,NotificationsComponent }
});


