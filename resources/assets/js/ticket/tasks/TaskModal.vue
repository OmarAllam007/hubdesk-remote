<template>
  <div>
    <button class="bg-blue-600 hover:bg-blue-700 px-3 py-1 rounded text-white m-5 show-modal" @click="openModal">show
      modal
    </button>

    <div
        class="modal h-screen w-full fixed left-0 top-0 flex justify-center items-center bg-black bg-opacity-50 hidden">
      <!-- modal -->
      <div class="bg-white h-auto  rounded shadow-lg w-1/2 ">
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
                     type="text" placeholder="Subject">
            </div>
            <div class="flex flex-col pt-5 ">
              <label for="template">Template:</label>
              <select class="border bg-white rounded px-3 py-2 outline-none" id="template"
                      name="template">
                <option value="" class="py-1">Select Template</option>
                <option :value="template.id" v-for="template in $parent.templates"
                        v-html="template.title"
                        class="py-1"></option>
              </select>
            </div>
            <div class="flex flex-col pt-5">
              <label for="task_description">Description <span class="text-red-600 ">*</span></label>
              <editor v-model="description" id="task_description"
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
                      name="template">
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
                               :name="`cf[${item.id}]`" :id="`cf[${item.id}]`"
                               class="pt-3" v-model="custom_fields[item.id]">
                    </component>
                  </div>
                </div>
              </div>
              <!--              <fields :fields="fields"></fields>-->
            </div>
          </div>
        </div>
        <div class="flex justify-end items-center w-100 border-t p-3">
          <button class="bg-blue-600 hover:bg-blue-700 px-3 py-1 rounded text-white">Create</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Editor from '@tinymce/tinymce-vue'
import axios from "axios";
import Fields from "../custom_fields/Fields";


import Date from "../custom_fields/Date";
import TextField from "../custom_fields/TextField";

export default {
  name: "TaskModal",
  mounted() {
    this.openModal();
  },
  data() {
    return {
      description: '', technicians: [], group: '',
      category_id: '', subcategory_id: '', item_id: '',
      subcategories: [], items: [], fields: [], custom_fields: []
    }
  },
  methods: {
    openModal() {
      const modal = document.querySelector('.modal');
      const showModal = document.querySelector('.show-modal');
      showModal.addEventListener('click', function () {
        modal.classList.remove('hidden')
      });
    },
    hideModal() {
      const modal = document.querySelector('.modal');
      const closeModal = document.querySelectorAll('.close-modal');
      closeModal.forEach(close => {
        close.addEventListener('click', function () {
          modal.classList.add('hidden')
        });
      });
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

      let url = `/custom_fields?category=${this.category_id}&subcategory=${this.subcategory_id}&item=${this.item_id}`;
      axios.get(url).then(response => {
        this.fields = response.data;
        if (response.data[""] != undefined) {
          // for (var i = 0; i < response.data[""].length; i++) {
          //   var fkey = response.data[""][i].name;
          //   this.custom_fields.push({'':''})
          // }
        }
        // for (const [key, value] in Object.entries(response.data)){
        //     console.log(key, value);
        // }
      });
    },

  },
  components: {Editor, Date, TextField},
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