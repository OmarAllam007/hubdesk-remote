<template>
  <div>
    <div class="flex flex-col">
      <div class="flex w-full" v-if="approvers.length">
        <div class="flex flex-col w-1/2 ">
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
        <label for="description">Description</label>

        <editor v-model="description" id="description"
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
      <div class="flex">
        <div class="flex flex-col w-1/2  pt-5 ">
          <!--        <div class="w-1/2">-->
          <label for="status">Change Status from ( {{ ticket.status }} ) to:</label>
          <select v-model="selected_status" class="border bg-white rounded px-3 py-2 outline-none" id="status"
                  name="status">
            <option :value="statusKey" v-for="(status, statusKey) in statuses"
                    class="py-1">{{ status }}
            </option>
          </select>
          <!--        </div>-->
        </div>

        <div class="flex flex-col w-1/2  pt-5 pl-5  ">
          <label for="attachments">Attachments: </label>
          <div class="form-group" id="attachments">
            <input type="file" class="form-control input-xs" name="attachments[]" @change="attachFiles($event)"
                   multiple>
          </div>
        </div>
      </div>

      <div class="flex w-full  ">
        <button class="uppercase px-8 py-2 rounded
         rounded-xl"
                :class="replyStyle"
                :disabled="!canSubmit">
          <i class="fa fa-send"></i> Reply
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import Select2 from 'v-select2-component';
import Editor from '@tinymce/tinymce-vue'

export default {
  name: "ReplyForm",
  props: ["ticket", 'statuses', 'approvers', 'templates'],
  data() {
    return {
      cc: [],
      selected_template: '',
      selected_status: 0,
      description: '',
      attachments: ''
    }
  },
  created() {
  },
  mounted() {
  },
  methods: {
    updateDescription() {
      this.templates.forEach((template) => {
        if (this.selected_template == template.id) {
          this.description = template.description;
          return;
        }
        this.description = ''
      })
    },
    attachFiles(event) {
      var files = event.target.files;

      for (var i = 0, file; file = files[i]; i++) {
        this.attachments.push(file)
      }
    },
  },
  computed: {
    canSubmit() {
      return this.description.length > 0;
    },
    replyStyle() {
      if (this.canSubmit) {
        return 'border border-blue-600 text-blue-600 max-w-max shadow-sm hover:shadow-lg hover:bg-blue-600 hover:text-white';
      }
      return 'border border-gray-500';
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