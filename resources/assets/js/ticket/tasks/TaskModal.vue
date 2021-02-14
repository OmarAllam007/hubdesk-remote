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
        <div class="border-b px-4 py-2 flex justify-between items-center">
          <h2 class="font-semibold text-xl">Create Task</h2>
          <button class="text-black close-modal" @click="hideModal">&cross;</button>
        </div>
        <!-- modal body -->
        <div class="p-3 flex flex-col ">
          <div class="flex w-full  pl-5 ">
            <div class="flex flex-col ">
              <div class="flex flex-col">
                <label for="subject">Subject</label>
                <input id="subject" class="shadow appearance-none border rounded w-full h-12
            py-2 px-3 text-grey-darker leading-tight focus:outline-none focus:shadow-outline"
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

            <div class="flex flex-col pl-2 justify-start">
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
            </div>
          </div>
        </div>
        <div class="flex justify-end items-center w-100 border-t p-3">
          <button class="bg-red-600 hover:bg-red-700 px-3 py-1 rounded text-white mr-1 close-modal">Cancel</button>
          <button class="bg-blue-600 hover:bg-blue-700 px-3 py-1 rounded text-white">Oke</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Editor from '@tinymce/tinymce-vue'
import axios from "axios";

export default {
  name: "TaskModal",
  mounted() {
    this.openModal();
  },
  data() {
    return {description: '', technicians: [], group: ''}
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
    }
  },
  components: {Editor},
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