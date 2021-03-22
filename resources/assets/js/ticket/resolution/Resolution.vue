<template>
  <div>
    <div class="flex flex-col  m-3 mt-10" v-if="resolution">
      <div class="flex justify-end pb-5 ">
        <button v-if="this.$parent.data.ticket.authorizations.can_resolve"
                class="bg-orange-500  hover:bg-orange-700  text-white
                hover:text-white font-bold py-2 px-4 rounded-full mr-2 ml-5 w-64 "
                @click="editResolution">
          <i class="fa fa-edit"></i> {{ $root.t('Edit') }}
        </button>
      </div>
      <reply :reply="resolution"></reply>
    </div>
    <div class="m-3 mt-10 flex flex-col" v-if="!resolution || edit_resolution">
      <loader v-show="loading"></loader>
      <div class="flex flex-col" v-if="!loading">
        <div class="flex w-1/2" v-if="templates.length">
          <div class="flex flex-col w-1/2">
            <label for="template">{{ $root.t('Reply Template') }}:</label>
            <select v-model="selected_template" class="border bg-white rounded px-3 py-2 outline-none" id="template"
                    name="template" @change="updateDescription">
              <option value="" class="py-1">Select Template</option>
              <option :value="template.id" v-for="template in templates" v-html="template.title"
                      class="py-1"></option>
            </select>
          </div>
        </div>

        <div class="flex flex-col w-full  pt-5">
          <label for="resolution_description">{{ $root.t('Description') }} <span class="text-red-600">*</span></label>
          <editor trigger="#" v-model="resolution_description" id="resolution_description"
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
       }"></editor>
        </div>
        <div class="flex">
          <div class="flex flex-col w-1/2  pt-5 ">
            <label for="attachments">{{ $root.t('Attachments') }}: </label>
            <div class="form-group" id="attachments">
              <input type="file" class="form-control input-xs" name="attachments[]" @change="attachFiles"
                     multiple>
            </div>
          </div>
        </div>

        <div class="flex w-full  ">
          <button class="uppercase px-5 py-2 rounded
                   rounded-xl" @click="submitResolution"
                  :class="replyStyle"
                  :disabled="!canSubmit">
            <i class="fa fa-send" v-if="!submit_loading"></i> <i class="fa fa-spin fa-spinner"
                                                                 v-if="submit_loading"></i>
            {{ edit_resolution ? $root.t('Update') : $root.t('Resolve') }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Editor from '@tinymce/tinymce-vue'
import Select2 from 'v-select2-component';
import Loader from "../_components/Loader";
import Reply from "../conversation/Reply";

import _ from 'lodash';
import axios from "axios";
import {EventBus} from "../../EventBus";

export default {
  name: "Resolution",
  data() {


    return {
      resolution_description: '',
      attachments: [],
      loading: false,
      submit_loading: false,
      selected_template: '',
      templates: [],
      ticket: '',
      resolution: '',
      can_resolve: false,
      edit_resolution: false,
    }
  },
  mounted() {
    this.loading = true;
    setTimeout(() => {
      this.loading = false;
      if (!_.isEmpty(this.$parent.data.ticket.resolution)) {
        this.resolution = this.$parent.data.ticket.resolution
      }
      this.can_resolve = this.$parent.data.ticket.authorizations.can_resolve
      this.templates = this.$parent.templates;
    }, 1000);
  },
  methods: {
    attachFiles(event) {
      var files = event.target.files;
      for (var i = 0, file; file = files[i]; i++) {
        this.attachments.push(file)
      }
    },
    updateDescription() {
      this.templates.forEach((template) => {
        if (this.selected_template == template.id) {
          this.resolution_description = template.description;
          return;
        }
        this.resolution_description = ''
      })
    },
    editResolution() {
      // this.selected_template = this.resolution.content;
      this.edit_resolution = !this.edit_resolution;
      this.resolution_description = this.resolution.content;
    },
    submitResolution() {
      let ticket = this.$parent.data.ticket;
      this.submit_loading = true;

      axios.post(`/ticket/resolution/${ticket.id}`, {'content': this.resolution_description,})
          .then((response) => {
            this.submit_loading = false;
            this.resolution = response.data.reply;

            EventBus.$emit('send_notification', 'replies',
                'Ticket Info', `Ticket has been resolved`, 'success');

            EventBus.$emit('add_resolution', {'reply': response.data.reply});
            EventBus.$emit('ticket_updated');
            EventBus.$emit('status_updated', response.data.reply);

            this.resetForm();
          }).catch((e) => {
        this.submit_loading = false;
      })
    },
    resetForm() {
      this.resolution_description = '';
      this.attachments = [];
      this.selected_template = '';
      this.edit_resolution = false;
    }
  },
  computed: {
    canSubmit() {
      return this.resolution_description.length > 0;
    },
    replyStyle() {
      if (this.canSubmit) {
        return 'border border-blue-600 text-blue-600 max-w-max shadow-sm hover:shadow-lg hover:bg-blue-600 hover:text-white';
      }
      return 'border border-gray-500';
    }
  },
  components: {Select2, Loader, Editor, Reply}

}
</script>

<style scoped>

</style>