import Vue from 'vue';
import axios from "axios";

window.axios = axios;

import SalarySlip from "./SalarySlip";

window.app = new Vue({
    el: '#salarySlip',
    data() {
        return {}
    },
    created() {

    },
    methods: {},
    components: {SalarySlip}
});