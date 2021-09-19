<template>
  <div v-if="isLetterModalOpened">
    <transition name="letter-modal">
      <div class="modal-mask ">
        <div class="modal-wrapper">
          <div class="modal-dialog modal-xl">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" @click="closeModal()"><i class="fa  fa-times"></i></button>
                <h3 class="modal-title text-2xl ">{{ $root.t('Letter Content') }}</h3>
              </div>
              <div class="modal-body">
                <div class="flex">
                  <div class="w-full">
                    <div class="flex flex-col pt-5 ">
                      <label>
                        {{ $root.t('Description') }}
                      </label>

                      <editor trigger="#" v-model="content"

                              :init="{
          paste_data_images: true,
         height: 200,
         menubar: false,
         plugins: [
            'advlist autolink lists link image imagetools charmap print preview anchor',
            'insertdatetime media table paste directionality textcolor colorpicker copy'
            ],
         toolbar:
           'undo redo | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent table | link | fontselect fontsizeselect | rtl | forecolor'
       }"

                      ></editor>
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
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
import Loader from "../_components/Loader";
import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css';
import Editor from '@tinymce/tinymce-vue'


export default {
  name: "LetterModal",
  components: {Loader, vSelect, Editor},
  props: ['isLetterModalOpened'],
  data() {
    return {
      content: ''
    }
  },
  mounted() {
    this.getLetterContent();
  },
  created() {

    EventBus.$on('openLetterModal', () => {
      // this.resetModal();
      console.log('opened');
    });

  },
  methods: {
    closeModal() {
      this.$emit('close-letter-modal');
    },

    getLetterContent() {
      axios.get(`/letters/get-letter-content/${this.$parent.ticketData.ticket.id}`).then((response) => {
        this.content = response.data;
        // EventBus.$emit('add_to_replies', {'reply': response.data.reply});
        //
        // this.closeModal();
        // this.resetModal();
        // EventBus.$emit('send_notification', 'replies',
        //     'Ticket Info', `Ticket has been forwarded successfully`, 'success');
      })
    },
    resetModal() {
      this.content = '';
    }
  },
  computed: {
    // can_forward() {
    //   return this.content != '' && this.to.length;
    // },
    submitClass() {
      if (this.can_forward) {
        return 'bg-green-600 hover:bg-green-800 cursor-pointer';
      }
      return 'bg-gray-500 hover:bg-gray-500 cursor-not-allowed'
    }
  },


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