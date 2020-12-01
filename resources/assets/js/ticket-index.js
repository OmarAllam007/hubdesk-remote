import Vue from 'vue';
import Criteria from './Criteria.vue';
import TicketIndex from "./ticket/index/TicketIndex";
window.axios = require('axios');

window.axios.defaults.headers.common = {
    'X-Requested-With': 'XMLHttpRequest',
    'X-CSRF-TOKEN' : document.querySelector('meta[name="csrf-token"]').getAttribute('content')
};

window.app = new Vue({
    el: '#TicketList',
    components: { Criteria ,TicketIndex}
});


