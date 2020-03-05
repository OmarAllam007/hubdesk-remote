import Vue from 'vue';
import ApprovalLevels from './ApprovalLevel.vue';
import ServiceRequirements from './ServiceRequirements.vue';
import AdditionalFee from '../js/additional-fees/AdditionalFee.vue';
import Availability from '../js/configurations/Availability';
// window.jQuery = window.$ = require('jquery');

window.app = new Vue({
    el: '#Levels',
    components: { ApprovalLevels ,ServiceRequirements,AdditionalFee,Availability}
})
