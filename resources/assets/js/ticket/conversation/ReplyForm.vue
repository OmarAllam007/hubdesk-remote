<template>
  <div>
    <loader v-show="loading"></loader>
    <!--    <div class="pt-10 ">-->
    <!--      <notifications group="replies" position="top right" width="40%" classes="notification-alert-style"/>-->
    <!--    </div>-->
    <div class="flex flex-col" v-if="!loading">

      <div class="flex w-full" v-if="approvers.length" v-cloak>
        <div class="flex flex-col w-1/2 ">
          <label for="cc">{{ $root.t('Cc') }}:</label>
          <v-select :options="approvers" label="text" name="cc" id="cc"
                    v-model="cc" placeholder="Select User" multiple
                    class="selection-list bg-white"></v-select>
        </div>

        <div class="flex flex-col w-1/2 pl-5 ">
          <label for="template" v-show="show_templates">{{ $root.t('Reply Template') }}:</label>
          <select v-model="selected_template" class="border bg-white rounded px-3 py-2 outline-none" id="template"
                  v-show="show_templates"
                  name="template" @change="updateDescription">
            <option value="" class="py-1">{{ $root.t('Select Template') }}</option>
            <option :value="template.id" v-for="template in templates" v-html="template.title"
                    class="py-1"></option>
          </select>
        </div>
      </div>

      <div class="flex flex-col w-full  pt-5 ">
        <label for="reply-description">{{ $root.t('Description') }} <span class="text-red-600 ">*</span></label>

        <editor trigger="#" v-model="description" id="reply-description"
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
      <div class="pt-5" v-if="ticket.status_id == 8"></div>
      <div class="flex" v-if="ticket.status_id != 8">
        <div class="flex flex-col w-1/2  pt-5 ">
          <!--        <div class="w-1/2">-->
          <label for="status">{{ $root.t('Change Status from') }} ( {{ $root.t(ticket.status) }} )
            {{ $root.t('to') }}:</label>
          <select v-model="selected_status" class="border bg-white rounded px-3 py-2 outline-none" id="status"
                  name="status">
            <option :value="statusKey" v-for="(status, statusKey) in statuses"
                    class="py-1">{{ $root.t(status) }}
            </option>
          </select>
          <!--        </div>-->
        </div>

        <div class="flex flex-col w-1/2  pt-5 pl-5  ">
          <label for="attachments">{{ $root.t('Attachments') }} : </label>
          <div class="form-group" id="attachments">
            <input type="file" class="form-control input-xs" name="attachments[]" @change="attachFiles"
                   multiple>
          </div>
        </div>
      </div>

      <div class="flex w-full  ">
        <button class="uppercase px-8 py-2 rounded
         rounded-xl" @click="submitReply"
                :class="replyStyle"
                :disabled="!canSubmit">
          <i class="fa fa-send"></i> {{ $root.t('Reply') }}
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import Select2 from 'v-select2-component';
import Editor from '@tinymce/tinymce-vue'
import Loader from "../_components/Loader";
import {EventBus} from "../../EventBus";
import vSelect from "vue-select";
import 'vue-select/dist/vue-select.css';
import _ from "lodash";

export default {
  name: "ReplyForm",
  props: ["ticket", 'statuses', 'approvers', 'templates', 'show_templates','authorizations'],
  mounted() {

    setTimeout(() => {
      $('.select2').select2({
        width: 'element',
        minimumResultsForSearch: Infinity
      }).on('select2:select', (e) => {
        var data = e.params.data;
        this.cc.push(data.id);
      });
    }, 2000);

  },
  created() {
    setTimeout(() => {
      $('.select2').select2({
        width: 'element',
        minimumResultsForSearch: Infinity
      }).on('select2:select', (e) => {
        var data = e.params.data;
        this.cc.push(data.id);
      });
    }, 2000);
  },
  data() {
    return {
      loading: false,
      cc: [],
      selected_template: '',
      selected_status: 0,
      description: '',
      attachments: []
    }
  },
  methods: {
    updateDescription() {
      let template = _.find(this.templates, {'id': this.selected_template});
      this.description = template ? template.description : '';
    },
    attachFiles(event) {
      var files = event.target.files;
      for (var i = 0, file; file = files[i]; i++) {
        this.attachments.push(file)
      }
    },
    submitReply() {
      this.loading = true;
      if (!this.description.length > 0) {
        return;
      }
      var reply = this.prepareData();

      axios.post(`reply/${this.ticket.id}`,
          reply, {
            headers: {
              'Content-Type': 'multipart/form-data'
            },
          })
          .then((response) => {
            if (response.data.error) {
              EventBus.$emit('send_notification', 'replies',
                  'Ticket Info', response.data.error, 'error');
              this.loading = false;
              return;
            }


            if (response.status == 200) {
              this.$parent.replies.unshift(response.data.reply);
              // EventBus.$emit('ticket-reply-saved')
              EventBus.$emit('send_notification', 'replies',
                  'Ticket Info', `Ticket reply has been added`, 'success');

              EventBus.$emit('ticket_updated');
              EventBus.$emit('status_updated', response.data.reply);

              this.resetForm();
            }

            this.loading = false;
          }).catch((error) => {
        this.loading = false;
        EventBus.$emit('send_notification', 'replies',
            'Ticket Error', `An error occurred while trying to send the reply`, 'error');
      });
    },
    resetForm() {
      this.selected_status = 0;
      this.selected_template = '';
      this.cc = [];
      this.description = '';
      this.attachments = [];
      setTimeout(() => {
        $('.select2').select2({
          width: 'element',
          minimumResultsForSearch: Infinity
        }).on('select2:select', (e) => {
          var data = e.params.data;
          this.cc.push(data.id);
        });
      }, 1000);
    },
    prepareData() {
      var reply = new FormData;
      reply.append('_token', $('meta[name="csrf-token"]').attr('content'));

      let attachments = this.attachments;
      for (var l = 0; l < attachments.length; l++) {
        reply.append(`attachments[${l}]`, attachments[l]);
      }

      reply.append('reply[content]', this.description)
      reply.append('reply[status]', parseInt(this.selected_status));
      reply.append('reply[status_id]', parseInt(this.selected_status));
      let ccEmails = this.cc;

      for (var c = 0; c < ccEmails.length; c++) {
        reply.append(`reply[cc][${c}]`, ccEmails[c].id);
      }
      reply.append('status_id', parseInt(this.selected_status));

      return reply;
    }
  },
  computed: {
    canSubmit() {
      return this.description.length > 0
          && this.description != "<p>&nbsp;</p>\n<div id=\"gtx-trans\" style=\"position: absolute; left: 605px; top: 26px;\">&nbsp;</div>";
    },
    replyStyle() {
      if (this.canSubmit) {
        return 'border border-blue-600 text-blue-600 max-w-max shadow-sm hover:shadow-lg hover:bg-blue-600 hover:text-white';
      }
      return 'border border-gray-500';
    }
  },
  components: {Select2, Editor, Loader, vSelect}
}
</script>

<style scoped>
[v-cloak] {
  display: none;
}

.cross-float {
  float: right
}

.selection-list {
  width: 100% !important;
}
</style>