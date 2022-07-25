import Vue from 'vue';
import Notifications from 'vue-notification';

Vue.use(Notifications);

import VueSweetalert2 from 'vue-sweetalert2';
// If you don't need the styles, do not connect
import 'sweetalert2/dist/sweetalert2.min.css';
import axios from 'axios';
Vue.use(VueSweetalert2)
import _ from 'lodash';
window.axios = axios;

import RecruitmentRequisition from "./RecruitmentRequisition";
window.app = new Vue({
    el: '#customRecruitmentRequisitionForm',
    data() {
        return {
            translations: [],
            loading: false
        }
    },
    created(){
    },
    methods: {
        t(word) {
            let targetWord = word.toLowerCase().replace("\n","");
            let translation = _.find(this.translations, {'word': targetWord});

            if (translation) {
                return translation.translation;
            }

            return word;
        }
    },
    components: {RecruitmentRequisition}
});