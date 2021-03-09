import Vue from 'vue';
import Charts from '../dashboard2/charts/Main.vue';
import VueHtmlToPaper from 'vue-html-to-paper';

Vue.config.productionTip = false;

const options = {
    name: '_blank',
    specs: [
        'fullscreen=no',
        'titlebar=yes',
        'scrollbars=yes'
    ],
    styles: [
        'https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css',
        'status_dashboard.css'
    ],
    localLandScapeOptions: {
        styles: [
            'https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css',
            'status_dashboard.css'
        ]
    }
}

Vue.use(VueHtmlToPaper, options);

new Vue({
    el: '#status_dashboard',
    components: {
        Charts
    }
});


