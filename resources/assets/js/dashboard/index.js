import Vue from 'vue';
import ServiceTypeChart from "./charts/ServiceTypeChart";
import YearlyPerformanceChart from "./charts/YearlyPerformanceChart.vue";
import CustomerSatisfactionChart from "./charts/CustomerSatisfactionChart";
import ServicePerformanceChart from "./charts/ServicePerformanceChart";
import StatusChart from "./charts/StatusChart";

Vue.config.productionTip = false;

new Vue({
    el: '#dashboard',
    components: {
        ServiceTypeChart, YearlyPerformanceChart,
        CustomerSatisfactionChart, ServicePerformanceChart,
        StatusChart
    }
});


