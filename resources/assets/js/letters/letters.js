import Vue from 'vue';
import LetterForm from "./LetterForm";
import Attachments from "../AttachmentModal";


window.axios = require('axios');

window.axios.defaults.headers.common = {
    'X-Requested-With': 'XMLHttpRequest',
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
};

window.app = new Vue({
    el: '#letters',
    components: {LetterForm,Attachments}
});


