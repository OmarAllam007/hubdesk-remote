<template>
  <div>
    <div class="flex flex-col">
      <div class="flex w-full" v-if="approvers.length">
        <div class="flex flex-col w-1/2">
          <label for="cc">Cc:</label>
          <Select2 v-model="cc" :options="approvers" id="cc" name="cc"
                   :settings="{ multiple:true ,placeholder:'Select User'}"/>
        </div>

        <div class="flex flex-col w-1/2 pl-5 ">
          <label for="template">Reply Template:</label>
          <select v-model="selected_template" class="border bg-white rounded px-3 py-2 outline-none" id="template"
                  name="template" @change="updateDescription">
            <option value="" class="py-1">Select Template</option>
            <option :value="template.id" v-for="template in templates" v-html="template.title"
                    class="py-1"></option>
          </select>
        </div>
      </div>
      <div class="flex flex-col w-full  pt-5 ">
        <editor v-model="description"
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
      <label for="status">Status:</label>
      <select v-model="selected_status" class="border bg-white rounded px-3 py-2 outline-none" id="status"
              name="status">
        <option :value="statusKey" v-for="(status, statusKey) in statuses"
                class="py-1">{{ status }}
        </option>
      </select>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import Select2 from 'v-select2-component';
import Editor from '@tinymce/tinymce-vue'

export default {
  name: "ReplyForm",
  props: ["ticket_id",'statuses','approvers','templates'],
  data() {
    return {
      cc: [],
      selected_template: '',
      selected_status: 0,
      description: ''
    }
  },
  created() {
    // this.getStatus();
    // this.getUsers();
    // this.getReplyTemplates();
  },
  mounted() {
  },
  methods: {
    getStatus() {
      axios.get(`/list/list-statuses/${this.ticket_id}`).then((response) => {
        this.statuses = response.data;
        this.loading = false;
      });
    },
    getUsers() {
      axios.get('/list/approvers-list').then((response) => {
        this.users = response.data;
        this.loading = false;
      });
    },
    getReplyTemplates() {
      axios.get('/list/reply-templates').then((response) => {
        this.templates = response.data;
        this.loading = false;
      });
    },
    updateDescription() {
      this.templates.forEach((template) => {
        if (this.selected_template == template.id) {
          this.description = template.description;
          return;
        }
        this.description = ''
      })
    }
  },
  components: {Select2, Editor}
}
</script>

<style scoped>
.cross-float {
  float: right
}

.selection-list {
  width: 500px !important;
}
</style>