import Vue from 'vue';
import Criteria from './Criteria.vue';

window.app = new Vue({
    el: '#kgs_criteria',
    name: 'kgs_criteria',
    components: {'kgs_criteria': Criteria}
});
