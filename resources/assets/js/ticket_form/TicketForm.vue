<template>
  <div>
    <notifications-component></notifications-component>
    <LoadingModal ref="modal" class="z-50"></LoadingModal>
    <div class="flex w-full justify-center" v-if="isLoading">
    </div>
    <div v-else>
      <div class="flex justify-center" v-if="ticket_attr.sla">
        <div class="w-1/2">
          <div class="flex rounded-3xl p-5 mb-5 justify-center  shadow-md"
               style="background-color: rgba(26, 29, 80, 0.9)">
            <div class="w-25 text-white pt-1  flex-col ">
              <i class="fa fa-info-circle fa-lg "></i>
            </div>
            <p class=" pl-2 pr-2 text-white text-2xl ">
              {{ $root.t('Your Request will Delivered within') }} {{ ticket_attr.sla.due_days }} {{ $root.t('Days') }}
              {{ ticket_attr.sla.due_hours }} {{ $root.t('Hours') }} {{ ticket_attr.sla.due_minutes }}
              {{ $root.t('Minutes') }} {{ $root.t('(from the last approval)') }}
            </p>
          </div>
        </div>
      </div>

      <div class="flex flex-col w-full  p-5 my-5  bg-white rounded-xl shadow-md">
        <users-list :is-technician="1"
                    @requester-changed="changeRequester"
                    :show_balance="show_balance"
                    :show_ticket_balance="show_ticket_balance"
                    :auth_user="auth_user"
                    v-if="create_for_others"
        ></users-list>

        <!--                <div class="w-full md:w-1/2 mb-6 md:mb-0">-->

        <div class="flex">
          <div class="w-1/2">
            <div class="flex-col">
              <label class="block w-full tracking-wide text-gray-700 font-bold mb-2" for="subject">
                {{ $root.t('Subject') }} <span class="text-red-700 font-bold">*</span>
              </label>
              <input class="px-4 py-2 w-full
        transition duration-300 border border-gray-400 rounded"
                     id="subject" type="text" v-model="form.subject">

              <p v-if="validations['ticket.subject']" class="text-red-700 pt-1">
                {{ $root.t(validations['ticket.subject'][0]) }}</p>
            </div>

          </div>
        </div>
      </div>


      <div class="flex flex-col w-full  p-5 my-5  bg-white rounded-xl shadow-md" v-for="section in fields" >
        <p class="bg-viola bg-opacity-75  text-white p-5  rounded-xl font-bold " v-if="section.title != ''">
          {{ $root.t(section.title) }}</p>
        <hr v-if="section.title != ''">
        <div class="flex flex-wrap w-full  mx-2  my-5 rounded-xl" >
          <div v-for="item in section.fields" class="w-full md:w-1/2 lg:w-1/2 xl:w-1/2 px-1 ">
            <component
                :is="item.type" :label="item.name"
                :name="`cf[${item.id}]`"
                :id="`cf[${item.id}]`"
                class="pt-5"
                v-model="form.custom_fields[item.id]"
                :options="item.options"
                :required="item.required"
                :item_id="item.id"
                @input="listenForChange"
                :type="item.type"
                :event_value="item.event_value"
            >
            </component>

            <p v-if="validations[item.id]" class="text-red-700 pt-1">{{ $root.t(validations[item.id]) }}</p>
          </div>
        </div>
      </div>


      <div class="flex flex-col w-full  p-5 my-5  bg-white rounded-xl shadow-md ">
        <label for="ticket-description "
               class="block  tracking-wide text-gray-700 font-bold mb-2">{{ $root.t('Description') }} <span
            class="text-red-700 font-bold">*</span></label>
        <editor trigger="#" v-model="form.description" id="ticket-description"
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

        <div class="flex flex-col pt-5 ">
          <label for="priorities"
                 class="font-semibold">
            {{ $root.t('Priority') }} <span class="text-red-700 font-bold">*</span>
          </label>

          <selectize
              class="w-1/2 px-1"
              v-model="form.priority_id">
            <option value="">Select Priority</option>
            <option v-for="priority in priorities" :value="priority.id">
              {{ $root.t(priority.name) }}
            </option>
          </selectize>

          <p v-if="validations['ticket.priority_id']" class="text-red-700 pt-1">
            {{ $root.t(validations['ticket.priority_id'][0]) }}</p>

        </div>
      </div>

      <div class="flex flex-col w-1/2   p-5 my-5  bg-white rounded-xl shadow-md" v-if="ticket_attr.notes != ''">
        <p class="bg-viola bg-opacity-75  text-white p-5  rounded-xl font-bold ">{{ $root.t('Notes') }}</p>
        <p v-html="ticket_attr.notes" class="pt-5 "></p>
      </div>

      <div class="flex flex-col w-full  p-5 my-5  bg-white rounded-xl shadow-md ">
        <p class="font-bold text-lg"> {{ $root.t('Attachments') }} <span
            class="text-gray-600"> [ {{ $root.t('Max Attachments size is') }} 5{{ $root.t('MB') }} ]</span></p>
        <ticket-form-attachments class="pt-5 " :errors="files_validation_error"></ticket-form-attachments>
      </div>

      <div class="flex w-full p-5 my-5 justify-center">

        <div class="flex w-full justify-center" v-if="isLoadingButton">
          <i class="fa fa-2x fa-spinner fa-spin"></i>
        </div>
        <button
            class="w-1/3 bg-transparent  text-white
          font-semibold hover:text-white py-2 px-4 rounded"
            :disabled="!can_submit"
            :class="can_submit_style"
            @click.prevent="submitTicket"
            v-else
        >
          <i class="fa fa-save"></i> {{ $root.t('Create Request') }}
        </button>

      </div>

    </div>

  </div>
</template>

<script>
import UsersList from "./UsersList";
import _ from "lodash";
import Editor from '@tinymce/tinymce-vue'
import TimeDate from "../ticket/custom_fields/TimeDate";
import Date from "../ticket/custom_fields/Date";
import TextField from "../ticket/custom_fields/TextField";
import SelectField from "../ticket/custom_fields/SelectField";
import TicketFormAttachments from "./TicketFormAttachments";
import axios from "axios";
import Selectize from 'vue2-selectize'
import "selectize/dist/css/selectize.bootstrap3.css";
import {EventBus} from "../EventBus";
import NotificationsComponent from "../ticket/show/NotificationsComponent";
import LoadingModal from "./LoadingModal";

export default {
  name: "TicketForm",
  components: {
    LoadingModal,
    TicketFormAttachments,
    UsersList,
    Editor,
    TimeDate,
    Date,
    TextField,
    SelectField,
    Selectize,
    NotificationsComponent,
  },
  props: {
    show_balance: {
      type: Number,
    },
    show_ticket_balance: {
      type: Number,
    },
    auth_user: {
      type: String,
    },
    create_for_others: {
      type: Number,
    },
    ticket_attr: {
      type: Object
    },
    priorities: {
      type: Array
    },
    translations: {
      type: Array
    },
    subject_text: {
      type: String
    },
  },


  data() {
    return {
      isLoading: false,
      isLoadingButton: false,
      isLoadingSAPFields:false,
      form: {
        requester_id: '',
        description: '',
        category_id: this.ticket_attr.category_id,
        subcategory_id: this.ticket_attr.subcategory_id,
        item_id: this.ticket_attr.item_id,
        subItem_id: this.ticket_attr.subitem_id,
        custom_fields: {},
        subject: '',
        files: [],
        creator_id: this.auth_user,
        priority_id: ''
      },

      fields: [],
      required_fields: [],
      refs: [],
      validFields: true,
      validations: {},
      files_validation_error: ''
    }

  },
  created() {
    this.loadFields();
    this.form.subject = this.subject_text
    this.$root.translations = this.translations

  },

  mounted() {
  },
  methods: {
    listenForChange(value) {
      this.form.custom_fields[value.id] = value.value
    },
    changeRequester(user) {
      if (user !== undefined) {
        this.form.requester_id = user.value.id;
      }
    },

    loadFromSAP(sapID) {
      this.loadingFromSAP = true
      axios.get(`/list/sap-info?id=${sapID}`).then((response) => {
        this.loadingFromSAP = false
        this.sapUser = response.data;

        EventBus.$emit('get_sap_user_information', {user: this.sapUser})
      }).catch((e) => {
        this.loadingFromSAP = false
      });
    },
    loadFields() {
      this.isLoading = true
      axios.get(`/list/fields?category_id=${this.form.category_id}&subcategory_id=${this.form.subcategory_id}&item_id=${this.form.item_id}&subItem_id=${this.form.subItem_id}`).then((response) => {
        if (!response.data.length) {
          this.isLoading = false
          return
        }
        Object.entries(response.data[0]).map((item) => {
          this.fields.push({
            title: item[0],
            fields: item[1],
          })

          item[1].forEach((value) => {
            this.form.custom_fields[value.id] = ''
          })
        })

        this.isLoading = false

      });
    },
    submitTicket() {
      var ticket = this.prepareData();
      this.files_validation_error = '';
      this.validations = {}
      this.isLoadingButton = true

      axios.post(`/ticket/post_new_ticket`,
          ticket, {
            headers: {
              'Content-Type': 'multipart/form-data'
            },
          })
          .then((response) => {
            if (response.data.error_code == 402 && response.data.file_size_error) {
              this.files_validation_error = response.data.file_size_error[0]

              EventBus.$emit('send_notification', 'create_ticket',
                  'Ticket Error', `Kindly check the attachments size`, 'error');
              this.isLoadingButton = false
              return;
            }

            if (response.data.error_code >= 400) {
              this.validations = response.data.errors
              EventBus.$emit('send_notification', 'create_ticket',
                  'Ticket Error', `Kindly fill all the required fields`, 'error');
              this.isLoadingButton = false
              return;
            }

            if (response.status == 200) {
              document.location.href = `/ticket/${response.data}`
              this.isLoadingButton = false
              EventBus.$emit('send_notification', 'create_ticket',
                  'Ticket Info', `Ticket created successfully`, 'success');
            }
          }).catch((error) => {
        this.isLoadingButton = false
      });


    },
    prepareData() {
      var ticket = new FormData;
      ticket.append('_token', $('meta[name="csrf-token"]').attr('content'));

      let attachments = this.form.files;
      for (var l = 0; l < attachments.length; l++) {
        ticket.append(`attachments[${l}]`, attachments[l]);
      }

      const fields = Object.keys(this.form.custom_fields).map(key => ({key, value: this.form.custom_fields[key]}));

      for (var cf = 0; cf < fields.length; cf++) {
        ticket.append(`ticket[fields]`, JSON.stringify(this.form.custom_fields));
      }

      ticket.append('ticket[description]', this.form.description)
      ticket.append('ticket[subject]', this.form.subject)
      ticket.append('ticket[category_id]', this.form.category_id)
      ticket.append('ticket[subcategory_id]', this.form.subcategory_id)
      ticket.append('ticket[item_id]', this.form.item_id)
      ticket.append('ticket[subitem_id]', this.form.subItem_id)
      ticket.append('ticket[requester_id]', this.form.requester_id)
      ticket.append('ticket[creator_id]', this.form.creator_id)
      ticket.append('ticket[priority_id]', this.form.priority_id)


      return ticket;
    }

  },

  computed: {
    can_submit() {
      return this.form.subject !== ''
    },
    can_submit_style() {
      return this.can_submit ? 'bg-green-500 hover:bg-green-600' : 'bg-gray-600 hover:bg-gray-600'
    }
  },

}
</script>

<style scoped>

</style>