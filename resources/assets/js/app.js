// import swal from 'sweetalert2'

import Vue from 'vue'

window.jQuery = window.$ = require('jquery');
window.swal = require('sweetalert2');

// window.swal = swal;
require('bootstrap-sass');
require('select2');

(function($) {
    $(function(){
        $('.select2').select2({width: '100%', allowClear: true});
    });
}(window.jQuery));
