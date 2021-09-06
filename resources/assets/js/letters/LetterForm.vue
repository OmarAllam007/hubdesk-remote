<template>
  <div>
    <div class="flex">
      <div class="w-1/2">
        <div class="form-group form-group-sm">
          <label>
            Subject
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
            Type<span class="text-red-800">*</span>
          </label>
          <v-select :options="groups" label="name" name="group" id="group"
                    v-model="group" placeholder="Select Type"
                    class="selection-list bg-white" @change="getSubGroups"></v-select>
        </div>
      </div>
    </div>

    <div class="flex" v-if="subgroups.length">
      <div class="w-1/2">
        <label for="subgroup">SubType<span class="text-red-800">*</span></label>
        <v-select id="subgroup" name="subgroups" :options="subgroups" label="name" v-model="subgroup"
                  placeholder="Letter subtype"
                  class="selection-list bg-white"></v-select>
      </div>
    </div>
    <br>
    <div class="flex" v-if="letters.length">
      <div class="w-1/2">
        <label for="letters">Letter<span class="text-red-800">*</span></label>
        <v-select id="letters" name="letters" :options="letters" label="name" v-model="letter"
                  placeholder="Select Letter"
                  class="selection-list bg-white"></v-select>
      </div>
    </div>

    <div class="flex pt-10" v-if="fields.length">
      <div class="w-1/2">
        <div v-for="(item, key) in fields">
          <component :is="item.type" :label="item.name"
                     :name="`cf[${item.id}]`" :id="`cf[${item.id}]`"
                     class="pt-3" v-model="fields[key]['value']" :options="item.options" >
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
          Stamped by the Chamber of Commerce?
        </label>
      </div>
    </div>


    <div class="flex pt-10">
      <div class="w-1/2">
        <div class="form-group">
          <label>
            Description
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
      <label for="attachments">Attachments</label>
      <div class="form-group" id="attachments">
        <input type="file" class="form-control input-xs" name="attachments[]" @change="attachFiles"
               multiple>
      </div>
    </div>

    <div class="flex pt-10">
      <button class="font-bold py-5 px-8 rounded-xl " :class="submitButtonStyle" :disabled="!canSubmit"
              @click.prevent="createLetter">
        <i class="fa fa-save"></i> Submit
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
import fields from "../Report/fields";

export default {
  name: "LetterForm",
  props: ['item', 'groups', 'priorities', 'subject'],
  components: {vSelect, Attachments, Editor ,Date, TextField, SelectField},

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
      //
      subgroups: [],
      letters: [],
      attachments: [],
      fields: [],
      custom_fields:[]
    }
  },
  watch: {
    group() {
      if (this.group) {
        this.getSubGroups();
        this.group_id = this.group.id;
      } else {
        this.subgroups = [];
        this.letters = [];
        this.group_id = null;
        this.subgroup_id = null;
        this.letter_id = null;
      }

    },
    subgroup() {
      if (this.subgroup) {
        this.getLetters();
        this.subgroup_id = this.subgroup.id;
        this.letters = [];

      } else {
        this.letters = [];
        this.letter = this.subgroup_id = this.letter_id = null;
      }
    },
    letter() {
      this.fields = [];
      if (this.letter != null) {
        this.letter_id = this.letter.id;
        this.getFields();
      }
    }
  },

  methods: {
    getSubGroups() {
      this.subgroup = null;
      this.subgroups = [];
      this.letters = [];

      axios.get(`/letters/list/subgroups/${this.group.id}`).then((response) => {
        if (!response.data.length) {
          this.getLetters();
        } else {
          this.subgroups = response.data;
        }

      });
    },

    getLetters() {
      this.group_id = this.subgroups.length ? this.subgroup.id : this.group.id;

      axios.get(`/letters/list/letters?group_id=${this.group_id}`).then((response) => {
        this.letters = response.data;
      });
    },

    getFields() {
      // if (this.letter == '' || this.letter_id == null) {
      //   return;
      // }
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

      for (var f = 0; f < fields.length; f++){
        form.append(`fields[${f}][id]`, fields[f].id);
        form.append(`fields[${f}][name]`, fields[f].name);
        form.append(`fields[${f}][value]`, fields[f].value);
      }

      form.append('description', this.description);
      form.append('subject', this.subject);
      form.append('item_id', this.item.id);
      form.append('group_id', this.group.id);

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
        console.log(response.data);
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
      let canSubmit = false;

      if (this.group) {
        canSubmit = true;
      }

      if (this.subgroups.length && !this.subgroup) {
        canSubmit = false;
      }

      if (!this.letter) {
        canSubmit = false;
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
.selection-list {
  /*width: 550px  !important;*/
}

</style>