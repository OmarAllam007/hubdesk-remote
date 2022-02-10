<template>
  <div>
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

    <div class="flex">
      <div class="w-1/2">
        <div class="form-group">
          <label for="group">
            {{ t('Type') }}
            <span class="text-red-800">*</span>
          </label>

          <select name="group" id="group" @change="getLetters" class="select2 group-select">
            <option value="">{{ t('Select Type') }}</option>
            <option v-for="group in groups" :value="group.id">{{ group.name }}</option>

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
          <component :is="item.type" :label="t(item.name) + '*'"
                     :name="`cf[${item.id}]`" :id="`cf[${item.id}]`"
                     class="pt-3" v-model="fields[key]['value']" :options="item.options">
          </component>
        </div>
      </div>
    </div>

    <div class="flex pt-10">
      <div class="w-1/2">
        <label :class="getStampedStyle" class="p-5  border border-indigo-700 bg-white
         hover:bg-yellow-100 rounded-2xl shadow-sm ">
          <input type="checkbox"
                 @change="is_stamped = !is_stamped">
          {{ t('Stamped by the Chamber of Commerce?') }}
        </label>
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
      <button class="font-bold py-5 px-8 rounded-xl " :class="submitButtonStyle" :disabled="!canSubmit"
              @click.prevent="createLetter">
        <i class="fa fa-save"></i> {{ t('Submit') }}
      </button>
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

export default {
  name: "LetterForm",
  props: ['item', 'groups', 'priorities', 'subject', 'translations', 'language', 'isTechnician'],
  components: {vSelect, Attachments, Editor, Date, TextField, SelectField},
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
      //
      users: [],
      letters: [],
      attachments: [],
      fields: [],
      custom_fields: [],
    }
  },
  created() {
    this.groups = this.groups.map((group) => {
      return {id: group.id, name: this.t(group.name)}
    });
    this.loadUsers()
  },
  mounted() {
    this.prepareDropList()
  },

  methods: {
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
      axios.get(`/list/employees?search=${searchText}`).then((response) => {
        this.users = response.data;
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

      axios.get(`/letters/list/letter_fields/${this.letter.id}`).then((response) => {
        this.fields = response.data;
      });

    },
    createLetter() {
      var form = new FormData;
      form.append('_token', $('meta[name="csrf-token"]').attr('content'));

      let attachments = this.attachments;

      for (var l = 0; l < attachments.length; l++) {
        form.append(`attachments[${l}]`, attachments[l]);
      }

      let fields = this.fields;

      for (var f = 0; f < fields.length; f++) {
        form.append(`fields[${f}][id]`, fields[f].id);
        form.append(`fields[${f}][name]`, fields[f].name);
        form.append(`fields[${f}][value]`, fields[f].value);
      }

      form.append('description', this.description ? this.description : '' );
      form.append('subject', this.subject);
      form.append('item_id', this.item.id);
      form.append('group_id', this.group.id);
      form.append('requester_id', this.user_id.id);

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
      let canSubmit = true;

      if (!this.group) {
        canSubmit = false;
      }

      if (!this.letter_id) {
        canSubmit = false;
      }

      if (this.fields.length) {
        this.fields.forEach((field) => {
          if (!field.value) {
            canSubmit = false;
          }
        })
      }

      return canSubmit;
    },
    submitButtonStyle() {
      if (this.canSubmit) {
        return 'bg-green-500 hover:bg-green-700 text-white'
      }
      return 'bg-gray-500 hover:bg-gray-700 text-white opacity-50 cursor-not-allowed'
    },
  }
}
</script>

<style scoped>

</style>