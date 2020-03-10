<script>
    import Vue from 'vue';
    import axios from 'axios'

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
                saving: false,
                fields: {},
                attachments: [],
                template: '',
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


                // this.saving = true;

                // let formData = new FormData();
                // for( let i = 0; i < this.files.length; i++ ) {
                //     formData.append('files[]', this.files[i]);
                // }
                // formData.append('category_id',this.category);
                // console.log(formData)
                // var cs = [];

                //Creates a formdata object for the upload, appends a CSRF token, the file itself and its respective name
                var formData = new FormData;
                formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

                for (var i = 0; i < this.attachments.length; i++) {
                    formData.append('attachments[]', this.attachments[i]);
                }

                formData.append('subject', this.subject);
                formData.append('category', this.category);
                formData.append('subcategory', this.subcategory);
                formData.append('item', this.item);
                formData.append('status', this.status);
                formData.append('description', tinyMCE.activeEditor.getContent());
                formData.append('group', this.group);
                formData.append('technician', this.technician);
                formData.append('ticket_id', this.ticket_id);
                formData.append('cf', this.fields);
                formData.append('template', this.template);
                jQuery.ajax({
                    url: '/ticket/tasks/' + this.ticket_id,
                    method: 'POST',
                    xhr: function () {
                        var myXhr = $.ajaxSettings.xhr();
                        return myXhr;
                    },

                    headers: {
                        // 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    dataType: 'json',
                    cache: false,
                    contentType: false,
                    processData: false,
                    async: true,
                    enctype: 'multipart/form-data',
                    data: formData,


                    success: (response) => {
                        this.saving = false;
                        this.loadTasks();
                        this.resetAll();
                        jQuery("#TaskForm").modal('hide');

                    },
                    error: (response) => {
                        this.saving = false;
                        this.errors = response.responseJSON;
                    }

                });


            },
            getCustomFields() {
                this.fields = {};
                $('#CustomFields').find('.cf').each((idx, element) => {
                    this.fields[element.id.substr(3)] = $(element).val()
                });
                this.fields = JSON.stringify(this.fields)
            }
            ,
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
            }
            ,
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

            }
            ,
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
            }
            ,
            loadSubcategory(withFields) {
                this.subcategories = [];
                this.subcategory = '';
                if (this.category) {
                    $.get(`/list/subcategory/${this.category}`).then(response => {
                        this.subcategories = response;
                    });
                }
                if (withFields) this.loadCustomFields();
            }
            ,
            loadItems(withFields) {
                console.log(this.subcategory)
                if (this.subcategory) {
                    $.get(`/list/item/${this.subcategory}`).then(response => {
                        this.items = response;
                    });
                }
                if (withFields) this.loadCustomFields();
            }
            ,
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
                this.technician = '';
                this.fields = [];
                this.template = ''
            }
            ,
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
            }
            ,
            loadTechnicians() {
                if (this.group) {
                    $.get(`/list/group-technicians/${this.group}`).then(response => {
                        this.technicians = response;
                    });
                }
            }
            ,

            handleFiles(e) {
                let uploadedFiles = this.$refs.attachments.files;

                for (var i = 0; i < uploadedFiles.length; i++) {
                    this.attachments.push(uploadedFiles[i]);
                }
                // this.files = formData
                // this.attachments = data;
            }
        },
        watch: {
            category() {
                this.loadSubcategory(true);

            }
            ,

            subcategory() {
                this.loadItems(true);
            }
            ,
            group() {
                this.loadTechnicians();
            }

        }
        ,
        mounted() {
            // this.loadSubcategory(true);
        },
        computed: {
            can_submit(){
                return (this.subject != '' && this.category != '') && this.saving == false
            }
        },
        created() {
            this.loadTasks();
            // this.loadSubcategory(true);
            // this.loadItems(true);
            this.loadTechnicians();
        }
    });

</script>

