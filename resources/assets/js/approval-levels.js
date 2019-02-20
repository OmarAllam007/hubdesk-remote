import Vue from 'vue';
import ApprovalLevels from './ApprovalLevel.vue';
window.jQuery = window.$ = require('jquery');

window.app = new Vue({
    el: '#Levels',
    components: { ApprovalLevels }
})
