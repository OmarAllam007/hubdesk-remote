<template>
  <div>
    <div
        class="modal w-full  fixed left-0 top-0 flex justify-center items-center bg-black bg-opacity-50 hidden">
      <!-- modal -->
      <div class="bg-white h-auto rounded shadow-lg w-full md:w-full  lg:w-1/2 ">
        <!-- modal header -->
        <div class="border-b px-4 py-2 flex justify-between items-center bg-viola bg-opacity-75 ">
          <h2 class="font-semibold text-2xl py-5 text-white">Create Task</h2>
          <button><i class="fa fa-times text-white close-modal" @click="hideModal"></i></button>
        </div>
        <!-- modal body -->
        <div class="p-3 flex">
          <div class="w-1/2">
            <div class="flex flex-col">
              <label for="subject">Subject</label>
              <input id="subject" class="px-4 py-2
        transition duration-300 border
        border-gray-300 rounded"
                     type="text" placeholder="Subject" v-model="subject">
            </div>
            <div class="flex flex-col pt-5 ">
              <label for="template">Template:</label>
              <select class="border bg-white rounded px-3 py-2 outline-none" id="template"
                      name="template" v-model="template_id" @change="getTemplateDescription">
                <option value="" class="py-1">Select Template</option>
                <option :value="template.id" v-for="template in $parent.templates"
                        v-html="template.title"
                        class="py-1"></option>
              </select>
            </div>
            <div class="flex flex-col pt-5">
              <label for="task_description">Description <span class="text-red-600 ">*</span></label>
              <editor trigger="#" v-model="description" id="task_description"
                      :init="{
          paste_data_images: true,
         height: 300,
         menubar: false,
         plugins: [
            'advlist autolink lists link image imagetools charmap print preview anchor',
        'insertdatetime media table paste directionality textcolor colorpicker'
         ],
         toolbar:
           'undo redo | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent table | link | fontselect fontsizeselect | rtl | forecolor'
       }"

              ></editor>
            </div>
          </div>

          <div class="w-1/2 pl-5 ">
            <div class="flex flex-col">
              <label for="group">Group</label>
              <select class="border bg-white rounded px-3 py-2 outline-none" id="group"
                      name="template" v-model="group" @change="loadTechnicians">
                <option value="" class="py-1">Select Group</option>
                <option :value="group.id" v-for="group in $parent.groups"
                        v-text="group.name"
                        class="py-1"></option>
              </select>
            </div>
            <div class="flex flex-col pt-5 ">
              <label for="technician_id">Technician</label>
              <select class="border bg-white rounded px-3 py-2 outline-none" id="technician_id"
                      name="template" v-model="technician_id">
                <option value="" class="py-1">Select Technician</option>
                <option :value="tech.id" v-for="tech in technicians"

                        class="py-1">{{ tech.name }}
                </option>
              </select>
            </div>
            <div class="flex flex-col pt-5">
              <label for="category_id">Category</label>
              <select class="border bg-white rounded px-3 py-2 outline-none" id="category_id"
                      name="category" @change="loadSubcategory" v-model="category_id">
                <option value="" class="py-1">Select Category</option>
                <option :value="category.id" v-for="category in $parent.categories"
                        class="py-1">{{ category.name }}
                </option>
              </select>
            </div>

            <div class="flex flex-col pt-5">
              <label for="subcategory_id">Subcategory</label>
              <select class="border bg-white rounded px-3 py-2 outline-none" id="subcategory_id"
                      name="subcategory" @change="loadItems" v-model="subcategory_id">
                <option value="" class="py-1">Select Subcategory</option>
                <option :value="subcategory.id" v-for="subcategory in subcategories"
                        class="py-1">{{ subcategory.name }}
                </option>
              </select>
            </div>

            <div class="flex flex-col pt-5">
              <label for="item_id">Item</label>
              <select class="border bg-white rounded px-3 py-2 outline-none" id="item_id"
                      name="item_id" v-model="item_id" @change="loadCustomFields">
                <option value="" class="py-1">Select Item</option>
                <option :value="item.id" v-for="item in items"
                        class="py-1">{{ item.name }}
                </option>
              </select>
            </div>
            <div class="flex flex-col pt-5" id="CustomFields">
              <div class="flex flex-col ">
                <div v-for="(items,title) in fields">
                  <p>{{ title }}</p>
                  <div v-for="(item,key) in items">
                    <!--          <text-field :label="item.name" v-if="item.type == 'textfield'"></text-field>-->
                    <component :is="item.type" :label="item.name"
                               :name="`cf[${item.id}]`" :id="`cf[${item.id}]`"h
                               class="pt-3" v-model="custom_fields[item.id]" :options="item.options">
                    </component>
                  </div>
                </div>
              </div>
              <!--              <fields :fields="fields"></fields>-->
            </div>
          </div>
        </div>
        <div class="flex flex-col w-1/2  pt-5 pl-5  ">
          <label for="attachments">Attachments: </label>
          <div class="form-group" id="attachments">
            <input type="file" class="form-control input-xs" name="attachments[]" @change="attachFiles"
                   multiple>
          </div>
        </div>
        <div class="flex justify-end items-center w-100 border-t p-3 ">
          <div class="cursor-not-allowed">
            <button :class="submitStyle"
                    @click="submitTask" :disabled="!canSubmit"><i class="fa fa-save"></i> Create
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Editor from '@tinymce/tinymce-vue'
import axios from "axios";

import Date from "../custom_fields/Date";
import TextField from "../custom_fields/TextField";
import SelectField from "../custom_fields/SelectField";
import {EventBus} from "../../EventBus";

export default {
  name: "TaskModal",
  created() {
    EventBus.$on('show-tasks-modal', (e) => {
      this.openModal();
    })
  },
  mounted() {
    // this.openModal();
  },
  data() {
    return {
      description: '',
      technicians: [],
      group: '',
      category_id: '',
      subcategory_id: '',
      item_id: '',
      subcategories: [],
      items: [],
      fields: [],
      custom_fields: {},
      subject: '',
      technician_id: '',
      template_id: '',
      attachments: [],
    }
  },
  methods: {
    openModal() {
      const modal = document.querySelector('.modal');
      modal.classList.remove('hidden')
    },
    hideModal() {
      const modal = document.querySelector('.modal');
      modal.classList.add('hidden')
    },
    loadTechnicians() {
      if (this.group) {
        axios.get(`/list/group-technicians/${this.group}`).then(response => {
          this.technicians = response.data;
        });
      }
    },
    loadSubcategory(withFields) {
      this.subcategories = [];
      this.subcategory_id = '';
      this.items = [];
      this.item_id = '';
      if (this.category_id) {
        axios.get(`/list/subcategory/${this.category_id}`).then(response => {
          this.subcategories = response.data;
        });
      }
      if (withFields) this.loadCustomFields();
    },
    loadItems(withFields) {
      this.items = [];
      this.item_id = '';
      if (this.subcategory_id) {
        axios.get(`/list/item/${this.subcategory_id}`).then(response => {
          this.items = response.data;
        });
      }
      if (withFields) this.loadCustomFields();
    },

    loadCustomFields() {
      let url = `/custom_fields?category=${this.category_id}&subcategory=${this.subcategory_id}&item=${this.item_id}`;
      axios.get(url).then(response => {
        this.fields = response.data;
      });
    },

    getTemplateDescription() {
      this.$parent.templates.filter((template) => {
        if (this.template_id == template.id) {
          this.description = template.description;
        } else {
          this.description = '';
        }
      })
    },
    attachFiles(event) {
      this.attachments = [];
      var files = event.target.files;
      for (var i = 0, file; file = files[i]; i++) {
        this.attachments.push(file)
      }
    },

    submitTask() {
      let task = this.prepareData();
      axios.post(`/ticket/tasks/${this.ticket_id}`,
          task, {
            headers: {
              'Content-Type': 'multipart/form-data'
            },
          })
          .then((response) => {
            if (response.status == 200) {
              this.$parent.tasks.push(response.data);
              EventBus.$emit('send_notification', 'tasks',
                  'Ticket Info', `Task #${response.data.id} has been created`, 'success');
              this.resetForm();
              this.hideModal();
              EventBus.$emit('ticket_updated');
            }
            this.loading = false;
          }).catch((error) => {
        this.loading = false;
        EventBus.$emit('send_notification', 'tasks',
            'Ticket Info', `Task #${id} has been created`, 'success');
      });
    },
    resetForm() {
      this.subject = '';
      this.description = '';
      this.attachments = [];
      this.group = '';
      this.technician_id = '';
      this.category_id = '';
      this.subcategory_id = '';
      this.item_id = '';
      this.custom_fields = {}
    },
    prepareData() {
      var task = new FormData;
      task.append('_token', $('meta[name="csrf-token"]').attr('content'));

      let attachments = this.attachments;
      for (var l = 0; l < attachments.length; l++) {
        task.append(`attachments[${l}]`, attachments[l]);
      }

      task.append('task[ticket_id]', this.$parent.ticket.id);
      task.append('task[subject]', this.subject);
      task.append('task[description]', this.description);
      task.append('task[category_id]', this.category_id);
      task.append('task[subcategory_id]', this.subcategory_id);
      task.append('task[item_id]', this.item_id);
      task.append('task[group]', this.group);
      task.append('task[technician_id]', this.technician_id);
      task.append('task[fields]', JSON.stringify(this.custom_fields));

      return task;
    },


  },
  computed: {
    submitStyle() {
      if (this.canSubmit) {
        return 'bg-blue-600 hover:bg-blue-700 px-5 py-3  rounded-2xl  text-white'
      }
      return 'bg-gray-600 hover:bg-gray-700 px-5 py-3  rounded-2xl  text-white cursor-not-allowed';
    },
    canSubmit() {
      return this.subject.length > 0 && this.category_id;
    }
  },

  components: {Editor, Date, TextField, SelectField},
}
</script>

<style scoped>
dialog[open] {
  animation: appear .15s cubic-bezier(0, 1.8, 1, 1.8);
}

dialog::backdrop {
  background: linear-gradient(45deg, rgba(0, 0, 0, 0.5), rgba(54, 54, 54, 0.5));
  backdrop-filter: blur(3px);
}


@keyframes appear {
  from {
    opacity: 0;
    transform: translateX(-3rem);
  }

  to {
    opacity: 1;
    transform: translateX(0);
  }
}
</style>