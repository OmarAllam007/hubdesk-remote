import Vue from 'vue';

window.app = new Vue({
    el: '#documentArea',
    data: {
        business_unit: window.business_unit,
        folder:window.folder,
        business_units:[],
        folders:[],
        document_id : null,
    },
    mounted: function () {
        this.loadFolders(true);
    },
    created() {
    },
    methods: {
        loadFolders() {
            if (this.business_unit) {
                jQuery.get(`/list/folders/${this.business_unit}`).then(response => {
                    this.folders = response;
                });
            }
        },
        changeDocumentId(id){
            console.log(id);
        }
    },

    watch: {
        business_unit() {
            this.folder = "";
            this.loadFolders();
        },


    },

});
