import Vue from 'vue';
import Criteria from './Criteria.vue';
import TicketIndex from './ticket/index/TicketIndex.vue'

window.app = new Vue({
    el: '#TicketList',
    components: {Criteria, TicketIndex}
});


