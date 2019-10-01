import Vue from 'vue';
import ReportParameters from './ReportParameters.vue';
import ScheduledReport from './ScheduledReport.vue';

window.app = new Vue({
    el: '#reports',

    components: { ReportParameters, ScheduledReport }
});
