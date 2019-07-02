<script>
    import Vue from 'vue';

    var $ = require('jquery');

    Vue.component('tasks', {
        props: ['ticket_id'],
        data() {
            return {
                category: window.category,
                subcategory: window.subcategory,
                item: window.item,
                tasks: [],
                errors: [],
                subject: '',
                description: '',
                subcategories: [],
                items: [],
                technicians: [],
                group: '',
                status: '',
                technician: '',
                edit: false,
                task_id: null,
                saving:false,
                fields:[]
            }
        },
        methods: {
            loadTasks() {
                $.ajax({
                    method: 'GET',
                    url: '/ticket/tasks/' + this.ticket_id,
                    success: function (response) {
                        this.tasks = response
                    }.bind(this),

                });
            },
            changeOnSubmit() {
                if (this.edit) {
                    this.updateTask();
                } else {
                    this.createTask();
                }
            },
            createTask() {
                this.getCustomFields()


                this.saving = true;
                var cs = [];
                jQuery.ajax({
                    method: 'POST',
                    url: '/ticket/tasks/' + this.ticket_id,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        subject: this.subject,
                        category: this.category,
                        subcategory: this.subcategory,
                        item: this.item,
                        status: this.status,
                        description: tinyMCE.activeEditor.getContent(),
                        group: this.group,
                        technician: this.technician,
                        ticket_id: this.ticket_id,
                        cf: this.fields,
                    },
                    success: function (response) {
                        this.loadTasks();
                        this.errors = response;
                        jQuery("#TaskForm").modal('hide');
                        this.saving=false;
                        this.resetAll();
                    }.bind(this),
                    error: function (response) {
                        this.errors = response.responseJSON;
                        this.saving =false;
                    }.bind(this)

                });
            },
            getCustomFields(){
                this.fields = [];
                $('#CustomFields').find('.cf').each((idx, element) => {
                        this.fields[element.id.substr(3,8)] = $(element).val()
                });
                console.log(this.fields)
            },
            editTask(task) {
                let modal = jQuery('#TaskForm');
                jQuery.ajax({
                    method: 'GET',
                    url: '/ticket/tasks/edit/' + task,
                    success: function (response) {
                        this.subject = response.subject;
                        this.description = response.description;
                        this.category = response.category_id;
                        this.subcategory = response.subcategory_id;
                        this.item = response.item_id;
                        this.status = response.status_id;
                        this.group = response.group_id;
                        this.technician = response.technician_id;
                        this.errors = [];
                        modal.find('.modal-title').html('Edit Task #' + task);
                        modal.modal('show');
                    }.bind(this),

                });
            },
            deleteTask(task) {
                $.ajax({
                    method: 'DELETE',
                    url: '/ticket/tasks/' + this.ticket_id + '/' + task,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        this.loadTasks()
                    }.bind(this),

                });

            },
            resetTask() {
                this.resetAll();
                jQuery('#TaskForm').find('.modal-title').html('Create Task');
            },
            updateTask() {
                jQuery.ajax({
                    method: 'PUT',
                    url: '/ticket/tasks/' + this.ticket_id,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        subject: this.subject,
                        description: this.description,
                        category: this.category,
                        subcategory: this.subcategory,
                        item: this.item,
                        status: this.status,
                        task_id: this.task_id,
                    },
                    success: function (response) {
                        this.loadTasks();
                        jQuery("#TaskForm").modal('hide');
                        this.resetAll();
                    }.bind(this),
                    error: function (response) {
                        this.errors = response.responseJSON
                    }.bind(this)

                });
            },
            loadSubcategory(withFields) {
                if (this.category) {
                    $.get(`/list/subcategory/${this.category}`).then(response => {
                        this.subcategories = response;
                    });
                }
                if (withFields) this.loadCustomFields();
            },
            loadItems(withFields) {
                if (this.subcategory) {
                    $.get(`/list/item/${this.subcategory}`).then(response => {
                        this.items = response;
                    });
                }
                if (withFields) this.loadCustomFields();
            },
            resetAll() {
                this.edit = false;
                this.subject = '';
                this.description = '';
                this.category = '';
                this.subcategory = '';
                this.item = '';
                this.cat = '';
                this.status = '';
                this.errors = [];
                this.subcategories = [];
                this.items = [];
                this.technicians = [];
                this.group = '';
                this.technician ='';
                this.fields = [];
            },
            loadCustomFields() {
                const customFieldsContainer = $('#CustomFields');
                const fieldValues = {};

                customFieldsContainer.find('.cf').each((idx, element) => {
                    let id = element.id;
                    let type = element.type;
                    if (type == 'checkbox') {
                        fieldValues[id] = element.checked;
                    } else {
                        fieldValues[id] = $(element).val();
                    }
                });

                let url = `/custom-fields?category=${this.category}&subcategory=${this.subcategory}&item=${this.item}`;
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
                });
            },
            loadTechnicians() {
                if (this.group) {
                    $.get(`/list/group-technicians/${this.group}`).then(response => {
                        this.technicians = response;
                    });
                }
            }
        }, watch: {
            category() {
                this.loadSubcategory(true);
            },

            subcategory() {
                this.loadItems(true);
            },
            group() {
                this.loadTechnicians();
            }

        },
        mounted(){
            // this.loadSubcategory(true);
        },
        created() {
            this.loadTasks();
            // this.loadSubcategory(true);
            // this.loadItems(true);
            this.loadTechnicians();
        }
    });
    window.app = new Vue({
        el: '#tasks',

    });
</script>

