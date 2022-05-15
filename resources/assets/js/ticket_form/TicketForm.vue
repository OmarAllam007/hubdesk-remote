<template>
  <div>

    <div class="flex w-full justify-center" v-if="isLoading">
        <i class="fa fa-3x fa-spinner fa-spin"></i>
    </div>
    <div v-else>


    <users-list :is-technician="1"
                @requester-changed="changeRequester"
                :show_balance="show_balance"
                :auth_user="auth_user"
                v-if="create_for_others"
    ></users-list>

    <div>
      <div class="w-full md:w-1/2 mb-6 md:mb-0">
        <label class="block  tracking-wide text-gray-700 font-bold mb-2" for="subject">
          {{ t('Subject') }} <span class="text-red-700 font-bold">*</span>
        </label>
        <input class=" block w-full  text-gray-700 border-none focus:border-black
          rounded py-3 px-4 mb-3 leading-tight  focus:bg-white"
               id="subject" type="text" :value="this.form.subject">
      </div>
    </div>

    <div class="flex flex-col w-full  p-5 my-5  bg-white rounded-xl shadow-md" v-for="section in fields">
      <p class="bg-viola bg-opacity-75  text-white p-5  rounded-xl font-bold " v-if="section.title != ''">
        {{ section.title }}</p>
      <hr v-if="section.title != ''">
      <div class="flex flex-wrap w-full  mx-2  my-5 rounded-xl">
        <div v-for="item in section.fields" class="w-full md:w-1/2 lg:w-1/2 xl:w-1/2 px-1 ">
          <component :is="item.type" :label="item.name"
                     :name="`cf[${item.id}]`" :id="`cf[${item.id}]`" h
                     class="pt-5 " v-model="form.custom_fields[item.id]"
                     :options="item.options"
                     :required="item.required"
                     @change-list="changeList"
                     :item_id="item.id"

          >
          </component>
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
    </div>
    <div class="flex flex-col w-full  p-5 my-5  bg-white rounded-xl shadow-md ">
      <p class="font-bold text-lg"> Attachments</p>
      <ticket-form-attachments class="pt-5 "></ticket-form-attachments>
    </div>

    <div class="flex w-full p-5 my-5 justify-center">

      <button
          class="w-1/3 bg-transparent bg-green-500 hover:bg-green-500 text-white font-semibold hover:text-white py-2 px-4 border border-green-500 hover:border-transparent rounded">
        <i class="fa fa-save"></i> Create
      </button>

    </div>

    </div>

  </div>
</template>

<script>
import UsersList from "./UsersList";
import _ from "lodash";
import Editor from '@tinymce/tinymce-vue'
import Date from "../ticket/custom_fields/Date";
import TextField from "../ticket/custom_fields/TextField";
import SelectField from "../ticket/custom_fields/SelectField";
import TicketFormAttachments from "./TicketFormAttachments";

export default {
  name: "TicketForm",
  components: {TicketFormAttachments, UsersList, Editor, Date, TextField, SelectField},
  props: {
    show_balance: {
      type: Number,
    },
    auth_user: {
      type: Number,
    },
    create_for_others: {
      type: Number,
    },
    ticket_attr: {
      type: Object
    },
    subject_text: {
      type: String
    }
  },


  data() {
    return {
      isLoading:false,
      form: {
        requester_id: '',
        description: '',
        category_id: this.ticket_attr.category_id,
        subcategory_id: this.ticket_attr.subcategory_id,
        item_id: this.ticket_attr.item_id,
        subItem_id: this.ticket_attr.subitem_id,
        custom_fields: {},
        subject: this.subject_text
      },

      fields: [],
      required_fields: [],

    }

  },
  created() {
    this.loadFields();
  },
  methods: {
    changeRequester(user) {
      if (user !== undefined) {
        this.form.requester_id = user.value.id
      }
    },
    t(word) {
      let translation = _.find(this.translations, {'word': word});

      if (translation) {
        return translation.translation;
      }
      return word;
    },
    loadFields() {
      this.isLoading = true
      axios.get(`/list/fields?category_id=${this.form.category_id}&subcategory_id=${this.form.subcategory_id}&item_id=${this.form.item_id}&subItem_id=${this.form.subItem_id}`).then((response) => {
        this.fields = Object.entries(response.data[0]).map((item) => ({
          title: item[0],
          fields: item[1],
        }))
        this.isLoading = false
        Object.entries(response.data[0]).map((item) => {
          this.required_fields.push(...item[1])
        })
      });
    },
    changeList($event){
      this.form.custom_fields[$event.id] = $event.value
    }
  },

}
</script>

<style scoped>

</style>