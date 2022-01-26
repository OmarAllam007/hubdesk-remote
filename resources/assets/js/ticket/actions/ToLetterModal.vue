<template>
  <div v-if="isToLetterOpened">
    <transition name="to-letter-modal">
      <div class="modal-mask ">
        <div class="modal-wrapper">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" @click="closeModal()"><i class="fa  fa-times"></i></button>
                <h3 class="modal-title text-2xl "><i class="fa fa-copy"></i> {{ $root.t('To Letter Ticket') }}</h3>
              </div>
              <div v-if="loading">
                <i class="fa fa-spin fa-spinner fa-2x"></i>
              </div>
              <div class="modal-body" v-else>
                <div class="flex">
                  <div class="w-1/2">
                    <div class="form-group form-group-sm">
                      <label>
                        Requester
                      </label>
                      <p v-if="user_id != ''"> {{ user_id.name }} </p>
                      <v-select
                          :options="users" label="employee_id"
                          v-model="user_id" id="employee_id" name="employee_id"
                          placeholder="Select user"
                          class="selection-list"></v-select>
                    </div>
                  </div>
                </div>
                <div class="flex">
                  <div class="w-1/2">
                    <div class="form-group form-group-sm">
                      <label>
                        Type
                      </label>
                      <v-select
                          :options="types" label="name"
                          v-model="letter_type" id="letter_type" name="letter_type"
                          placeholder="Select Type"
                          class="selection-list" @input="getLetters"></v-select>
                    </div>
                  </div>
                </div>

                <div class="flex" v-if="letters.length">
                  <div class="w-1/2">
                    <div class="form-group form-group-sm">
                      <label>
                        Letters
                      </label>
                      <v-select
                          :options="letters" label="name"
                          v-model="letter" id="letter" name="letter"
                          placeholder="Select Letter"
                          class="selection-list" @input="getFields"></v-select>
                    </div>
                  </div>
                </div>

                <div class="flex pt-5" v-if="fields.length">
                  <div class="w-1/2">
                    <div v-for="(item, key) in fields">
                      <component :is="item.type" :label="item.name + '*'"
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
                      Stamped by the Chamber of Commerce?
                    </label>
                  </div>
                </div>

              </div>
              <div class="modal-footer">
                <button
                    :class="submitClass"
                    class="text-white font-bold py-2 px-4  hover:text-white rounded-xl  mr-2 shadow-md pb-2 mb-2 "
                    @click="convert"
                    :disabled="!can_convert">
                  <i class="fa fa-check-circle"></i>
                  {{ $root.t('Convert') }}
                </button>
                <button type="button"
                        class="bg-white hover:text-gray-800    font-bold py-2 px-4  hover:text-white rounded-xl  mr-2 pb-2 mb-2 "
                        data-dismiss="modal" @click="closeModal()">
                  {{ $root.t('Close') }}
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
import {EventBus} from "../../EventBus";
import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css';
import Date from "../custom_fields/Date";
import TextField from "../custom_fields/TextField";
import SelectField from "../custom_fields/SelectField";

export default {
  name: "ToLetterModal",
  props: ["isToLetterOpened"],
  components: {vSelect, Date, TextField, SelectField},
  data() {
    return {
      loading: false,
      user_id: '',
      users: [],
      types: [],
      letters: [],
      fields: [],
      letter_type: '',
      letter: '',
      is_stamped: false
    }
  },
  created() {
    this.loadUsers()
    this.loadLetterTypes()
  },
  methods: {
    closeModal() {
      this.$emit('close-to-letter-modal');
    },
    loadUsers() {
      this.loading = true
      axios.get(`/list/employees/`).then((response) => {
        this.users = response.data;
        this.loading = false
      });
    },


    loadLetterTypes() {
      this.loading = true
      axios.get(`/letters/list/letter_group`).then((response) => {
        this.types = response.data;
        this.loading = false
      });
    },
    getLetters() {
      this.letter = ''
      this.letters = []
      this.fields = []
      // this.group_id = this.subgroups.length ? this.subgroup.id : this.group.id;

      axios.get(`/letters/list/letters?group_id=${this.letter_type.id}`).then((response) => {
        this.letters = response.data;
      });
    },
    getFields() {
      axios.get(`/letters/list/letter_fields/${this.letter.id}`).then((response) => {
        this.fields = response.data;
      });

    },
    convert() {
      var form = new FormData;
      form.append('_token', $('meta[name="csrf-token"]').attr('content'));

      let fields = this.fields;

      for (var f = 0; f < fields.length; f++) {
        form.append(`fields[${f}][id]`, fields[f].id);
        form.append(`fields[${f}][name]`, fields[f].name);
        form.append(`fields[${f}][value]`, fields[f].value);
      }


      form.append('requester_id', this.user_id.id);
      form.append('group_id', this.letter_type.id);
      form.append('letter_id', this.letter.id);
      form.append('is_stamped', this.is_stamped === true ? 1 : 0);
      form.append('ticket_id', this.$parent.data.ticket.id);

      axios.post('/letters/convert-to-letter', form, {
        headers: {
          'Content-Type': 'multipart/form-data'
        },
      }).then((response) => {
        location.reload();
      });


    }

  },
  computed: {
    getStampedStyle() {
      if (this.is_stamped) {
        return 'bg-gray-200 rounded-2xl'
      }
    },
    can_convert() {
      return this.user_id != '' && this.letter_type != '' && this.letter != '';
    },
    submitClass() {
      if (this.can_convert) {
        return 'bg-green-600 hover:bg-green-800 cursor-pointer';
      }
      return 'bg-gray-500 hover:bg-gray-500 cursor-not-allowed'
    }
  },
  watch: {
    // number_of_tickets() {
    //   if (this.number_of_tickets <= 0) {
    //     this.number_of_tickets = 1;
    //   }
    // }
  }

}
</script>

<style scoped>
.select2-container {
  z-index: 9999;
}


.modal-mask {
  position: fixed;
  /*z-index: 9998;*/
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, .5);
  display: table;
  transition: opacity 0.5s ease-in-out;

}

.modal-wrapper {
  display: table-cell;
  vertical-align: middle;
}
</style>