import Vue from 'vue';
import LetterForm from "./LetterForm";
import Attachments from "../AttachmentModal";
import _ from "lodash";


window.axios = require('axios');

window.axios.defaults.headers.common = {
    'X-Requested-With': 'XMLHttpRequest',
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
};

window.app = new Vue({
    el: '#letters',
    components: {LetterForm,Attachments},
    methods:{
        t(word) {
            let targetWord = word.toLowerCase().replace("\n","");
            let translation = _.find(this.translations, {'word': targetWord});

            if (translation) {
                return translation.translation;
            }

            return word;
        }
    }

});


