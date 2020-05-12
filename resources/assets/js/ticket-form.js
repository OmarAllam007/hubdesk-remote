import Vue from 'vue';
import Attachments from './AttachmentModal.vue';
import ApprovalQuestions from "./approvals/questions/ApprovalQuestions";
import Task from './Task.vue';


window.app = new Vue({
    el: '#ticketArea',
    data: {
        category: window.category,
        subcategory: window.subcategory,
        item: window.item,
        subitem: window.subitem,
        group: window.group,
        subcategories: {},
        items: {},
        subitems: {},
        technicians: {},
        technician_id: window.technician_id,
        requester: '',
        loading: false,
        requester_id: '',
        selected: 0
    },
    mounted: function () {
        this.loadCategory(true);
        this.loadSubcategory(true);
        this.loadItem(true);
        this.loadSubItem(true);

        $("#requester_id").change((e) => {
            this.getRequesterInfo(e.target.value)
        });
    },
    created() {
        this.getRequesterInfo($('#requester_id').val())
        this.loadTechnicians()

    },
    methods: {
        loadCategory(withFields) {
            if (this.category) {
                jQuery.get(`/list/subcategory/${this.category}`).then(response => {
                    this.subcategories = response;
                });

                if (withFields) this.loadCustomFields();
            }
        },

        loadSubcategory(withFields) {
            if (this.subcategory && this.subcategory != 0) {
                jQuery.get(`/list/item/${this.subcategory}`).then(response => {
                    this.items = response;
                });

                if (withFields) this.loadCustomFields();
            }
        },
        loadTechnicians() {
            if (this.group) {
                jQuery.get(`/list/group-technicians/${this.group}`).then(response => {
                    this.technicians = response;
                });
            }

        },
        loadItem(withFields) {
            if (this.item && this.item != 0) {
                jQuery.get(`/list/subitem/${this.item}`).then(response => {
                    this.subitems = response;
                });
                if (withFields) this.loadCustomFields();
            }
        },
        loadSubItem(withFields) {
            if (this.subitem && this.subitem != 0) {
                if (withFields) this.loadCustomFields();
            }
        },
        loadCustomFields() {
            const $ = window.jQuery;
            const customFieldsContainer = $('#CustomFields');
            const fieldValues = {};

            customFieldsContainer.find('.cf').each(function (idx, element) {
                let id = element.id;
                let type = element.type;
                if (type == 'checkbox') {
                    fieldValues[id] = element.checked;
                } else {
                    fieldValues[id] = $(element).val();
                }
            });

            let url = `/custom-fields?category=${this.category}&subcategory=${this.subcategory}&item=${this.item}&subitem=${this.subitem}`;
            $.get(url).then(response => {
                let newFields = $(response);
                for (let id in fieldValues) {
                    const field = newFields.find('#' + id);
                    if (field.attr('type') == 'checkbox') {
                        field.prop('checked', fieldValues[id]);
                    } else {
                        field.val(fieldValues[id]);
                    }
                }
                customFieldsContainer.html('').append(newFields);
                $('.cf.select2').select2({width: '100%', allowClear: true});
            });
        },
        getRequesterInfo(id) {
            this.loading = true;
            $.get(`/get-requester-info/${id}`).then(response => {
                this.requester = response;
                this.loading = false;
            });
        }
    },

    watch: {
        category() {
            this.subcategory = ""
            this.item = ""

            this.subcategories = {};
            this.items = {};
            this.subitems = {};

            this.loadCategory(true);
        },

        subcategory() {
            this.item = ""
            this.loadSubcategory(true);
            this.items = {};
            this.subitems = {};
        },

        item() {
            this.loadItem(true);
            this.subitems = {};
        },

        subitem() {
            this.loadSubItem(true);
        },

        group() {
            console.log('asd');
            this.loadTechnicians();
        },
    },

    components: {Attachments,Task, ApprovalQuestions}
});
