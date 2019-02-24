import Vue from 'vue';
import Roles from './Roles.vue';
window.jQuery = window.$ = require('jquery');

window.app = new Vue({
    el: '#BusinessRoles',
    components: { Roles }
})
