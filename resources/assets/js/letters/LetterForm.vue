<template>
  <div>
    <notifications-component></notifications-component>
    <div class="flex flex-col w-full  p-5 my-5  bg-white rounded-xl shadow-md">

      <div class="flex" v-if="isTechnician == 1">
        <div class="w-1/2">
          <div class="form-group form-group-sm">
            <label for="requester_id">
              {{ t('Requester') }}
            </label>
            <v-select
                :options="users" label="employee_id" v-model="user_id" id="requester_id" name="requester_id"
                :placeholder="t('Created For Me')"
                class="selection-list bg-white" @search="searchForUser"></v-select>
            <p v-if="user_id">{{ user_id.name }}</p>
          </div>
        </div>
      </div>

      <div class="flex">
        <div class="w-1/2">
          <div class="form-group form-group-sm">
            <label>
              {{ t('Subject') }}
            </label>
            <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3
          text-gray-700 leading-tight focus:outline-none focus:shadow-outline" v-model="subject" disabled>
          </div>
        </div>
      </div>
    </div>

    <div class="flex flex-col w-full  p-5 my-5  bg-white rounded-xl shadow-md">

      <div class="flex">
        <div class="w-1/2">
          <div class="form-group">
            <label for="group">
              {{ t('Type') }}
              <span class="text-red-800">*</span>
            </label>

            <select name="group" id="group" @change="getLetters" class="select2 group-select">
              <option value="">{{ t('Select Type') }}</option>
              <option v-for="group in list_groups" :value="group.id">{{ group.name }}</option>
            </select>

          </div>
        </div>
      </div>
      <br>
      <div class="flex justify-center " v-if="load_letters">
        <div class="w-1/2">
          <span><i class="fa fa-spinner fa-spin fa-2x "></i></span>
        </div>
      </div>
      <div class="flex" v-show="letters.length && !load_letters">
        <div class="w-1/2">
          <label for="letter">{{ t('Letter') }}<span class="text-red-800">*</span></label>
          <select name="letter" id="letter" class="select2 letter-select">
            <option value="">{{ t('Select Letter') }}</option>
            <option v-for="letter in letters" :value="letter.id">{{ letter.name }}</option>
          </select>
        </div>
      </div>

      <div class="flex pt-10" v-show="fields.length">
        <div class="w-1/2">
          <div v-for="(item, key) in fields">
            <component
                :is="item.type" :label="t(item.name)"
                :name="`cf[${item.id}]`" :id="`cf[${item.id}]`"
                class="pt-5 "
                v-model="custom_fields[item.id]"
                :options="item.options"
                :required="item.required"
                :item_id="item.id"
                @input="listenForChange"
                :type="item.type"
            >
            </component>
          </div>
        </div>
      </div>

      <div class="flex pt-10">
        <div class="w-1/2">
          <label :class="getStampedStyle" class="p-5  border border-indigo-700 bg-white
         hover:bg-yellow-100 rounded-2xl shadow-sm ">
            <input type="checkbox"
                   @change="changeStamp">
            {{ t('Stamped by the Chamber of Commerce?') }}
          </label>
        </div>
      </div>

      <div class="flex pt-10" v-if="showTransferMethod">
        <div class="w-1/2">
          <label for="paymentType">{{ t('Payment Type') }}<span class="text-red-800">*</span></label>

          <select name="paymentType" id="paymentType" class="form-control" v-model="paymentTypeSelection">
            <option value="">{{ t('Select Type') }}</option>
            <option :value="1">{{ t('Transfer to bank') }}</option>
            <option :value="2">{{ t('Deduction from salary') }}</option>
          </select>
          <p>
            <label class="pt-5 " v-if="paymentTypeSelection === 1">Bank Name: {{ bank_name }}</label>
          </p>


          <p>
            <label class="pt-5 " v-if="paymentTypeSelection === 1">Transfer to IBAN: {{ iban }}</label>
          </p>

        </div>
      </div>


      <div class="flex pt-10">
        <div class="w-1/2">
          <div class="form-group">
            <label>
              {{ t('Description') }}
            </label>

            <editor trigger="#" v-model="description"

                    :init="{
          paste_data_images: true,
         height: 200,
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
      </div>

      <div class="flex flex-col w-1/2  pt-10">
        <label for="attachments">{{ t('Attachments') }}</label>
        <div class="form-group" id="attachments">
          <input type="file" class="form-control input-xs" name="attachments[]" @change="attachFiles"
                 multiple>
        </div>
      </div>

      <div class="flex pt-10">
        <i class="fa fa-2x fa-spin fa-spinner" v-if="loading"></i>
        <button class="font-bold py-5 px-8 rounded-xl " :class="submitButtonStyle" :disabled="!canSubmit || loading"
                v-else
                @click.prevent="createLetter">
          <i class="fa fa-save"></i> {{ t('Submit') }}
        </button>
      </div>
    </div>
    <br>
  </div>
</template>
<script>

import 'vue-select/dist/vue-select.css';
import vSelect from "vue-select";
import Attachments from "../AttachmentModal";
import Editor from '@tinymce/tinymce-vue'
import Date from "../ticket/custom_fields/Date";
import TextField from "../ticket/custom_fields/TextField";
import SelectField from "../ticket/custom_fields/SelectField";
import _ from "lodash";
import {EventBus} from "../EventBus";
import NotificationsComponent from "../ticket/show/NotificationsComponent";


export default {
  name: "LetterForm",
  props: ['item', 'groups', 'priorities', 'subject', 'translations', 'language', 'isTechnician', 'iban','bank_name'],
  components: {vSelect, Attachments, Editor, Date, TextField, SelectField, NotificationsComponent},
  data() {
    return {
      group: '',
      subgroup: '',
      letter: '',
      group_id: null,
      subgroup_id: null,
      letter_id: null,
      is_stamped: false,
      description: '',
      user_id: '',
      load_letters: false,
      loading: false,

      users: [],
      letters: [],
      attachments: [],
      fields: [],
      custom_fields: {},
      list_groups: [],
      showTransferMethod: false,
      paymentTypeSelection: '',
    }
  },
  created() {
    this.list_groups = this.groups.map((group) => {
      return {id: group.id, name: this.t(group.name)}
    });
    this.loadUsers()
  },
  mounted() {
    this.prepareDropList()
  },

  methods: {
    changeStamp() {
      this.is_stamped = !this.is_stamped
      this.showTransferMethod = !this.showTransferMethod
    },
    listenForChange(value) {
      this.custom_fields[value.id] = value.value
    },
    searchForUser(text) {
      if (text.length > 3) {
        this.loadUsers(text);

      }
    },
    t(word) {
      let translation = _.find(this.translations, {'word': word});

      if (translation) {
        return translation.translation;
      }
      return word;
    },

    prepareDropList() {
      $('.user-select').on('select2:selecting', (e) => {
        this.user_id = e.params.args.data['id'];
      });
      $('.group-select').on('select2:selecting', (e) => {
        this.fields = []

        this.group = e.params.args.data;
        this.group_id = this.group.id;

        this.getLetters();
      });

      $('.letter-select').on('select2:selecting', (e) => {
        this.letter = e.params.args.data;
        this.letter_id = e.params.args.data['id'];
        this.getFields();
      });
    },

    loadUsers(searchText = '') {
      this.loading = true
      axios.get(`/list/employees?search=${searchText}`).then((response) => {
        this.users = response.data;
        this.loading = false
      });
    },

    getLetters() {
      this.load_letters = true;
      this.letters = [];
      axios.get(`/letters/list/letters?group_id=${this.group_id}`).then((response) => {
        this.letters = response.data;
        this.letters = this.letters.map((letter) => {
          return {id: letter.id, name: this.t(letter.name)}
        });
        this.load_letters = false;
      });
    },

    getFields() {
      this.fields = [];
      this.custom_fields = {};

      axios.get(`/letters/list/letter_fields/${this.letter.id}`).then((response) => {
        if (!response.data.length) {
          this.isLoading = false
          return
        }
        this.fields = response.data

        Object.entries(response.data).map((item) => {
          this.custom_fields[item[1].id] = ''
        })
      });

    },

    validateFields() {
      var isValid = true;

      if (_.size(this.custom_fields) > 0) {
        Object.values(this.custom_fields).forEach((value) => {
          if (value == "") {
            isValid = false;
          }
        })
      }
      return isValid

    },

    createLetter() {

      if (this.validateFields()) {
        this.loading = true

        var form = new FormData;
        form.append('_token', $('meta[name="csrf-token"]').attr('content'));

        let attachments = this.attachments;

        for (var l = 0; l < attachments.length; l++) {
          form.append(`attachments[${l}]`, attachments[l]);
        }

        const fields = Object.keys(this.custom_fields).map(key => ({key, value: this.custom_fields[key]}));

        for (var cf = 0; cf < fields.length; cf++) {
          form.append(`fields`, JSON.stringify(this.custom_fields));
        }


        form.append('description', this.description ? this.description : '');
        form.append('subject', this.subject);
        form.append('item_id', this.item.id);
        form.append('group_id', this.group.id);
        form.append('requester_id', this.user_id.id);
        form.append('payment_type', this.paymentTypeSelection);

        if(this.paymentTypeSelection === 1){
          form.append('iban', this.iban);
        }

        if (this.subgroup) {
          form.append('subgroup_id', this.subgroup.id);
        }
        form.append('letter_id', this.letter.id);
        form.append('is_stamped', this.is_stamped === true ? 1 : 0);


        axios.post('/letters/create-letter-ticket', form, {
          headers: {
            'Content-Type': 'multipart/form-data'
          },
        }).then((response) => {
          window.location = `/ticket/${response.data.ticket.id}`
        });
      } else {
        EventBus.$emit('send_notification', 'create_ticket',
            'Ticket Error', `Kindly fill all the required fields`, 'error');
      }


    },

    attachFiles(event) {
      var files = event.target.files;
      for (var i = 0, file; file = files[i]; i++) {
        this.attachments.push(file)
      }
    },
  },
  computed: {
    getStampedStyle() {
      if (this.is_stamped) {
        return 'bg-gray-200 rounded-2xl'
      }
    },
    canSubmit() {
      console.log(_.size(this.custom_fields))

      if (!this.group_id) {
        return false
      }

      if (this.is_stamped && !this.paymentTypeSelection) {
        return false
      }

      if (!this.letter_id) {
        return false
      }

      return true;

    },
    submitButtonStyle() {
      if (this.canSubmit && !this.loading) {
        return 'bg-green-500 hover:bg-green-700 text-white'
      } else {
        return 'bg-gray-500 hover:bg-gray-700 text-white opacity-50 cursor-not-allowed'
      }

    },
  }
}
</script>

<style scoped>

</style>