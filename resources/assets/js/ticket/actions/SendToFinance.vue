<template>
  <div v-if="isFinanceOpened">
    <transition name="forward-modal">
      <div class="modal-mask ">
        <div class="modal-wrapper">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" @click="closeModal()"><i class="fa  fa-times"></i></button>
                <h3 class="modal-title text-2xl ">{{ $root.t('Send To Finance') }}</h3>
              </div>
              <div class="modal-body">
                <div class="flex">
                  <div class="w-full">
                    <div class="flex flex-col">
                      <label for="to">{{ $root.t('To') }}:</label>
                      <v-select multiple
                                :options="$parent.users" label="text" v-model="to" id="to" name="to"
                                placeholder="Select user"
                                class="selection-list"></v-select>
                    </div>

                    <div class="flex flex-col pt-5 ">
                      <label>
                        {{ $root.t('Description') }}
                      </label>

                      <editor trigger="#" v-model="description"

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
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button
                    :class="submitClass"
                    class="text-white font-bold py-2 px-4  hover:text-white rounded-xl  mr-2 shadow-md pb-2 mb-2 "
                    @click="forward"
                    :disabled="!can_send">
                  <i class="fa fa-check-circle"></i>
                  {{ $root.t('Send') }}
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
import Loader from "../_components/Loader";
import vSelect from "vue-select";
import Editor from "@tinymce/tinymce-vue";
import {EventBus} from "../../EventBus";

export default {
  name: "SendToFinance",
  components: {Loader, vSelect, Editor},
  props: ['isFinanceOpened'],
  data() {
    return {
      to: [],
      description: ''
    }
  },

  created() {
    EventBus.$on('openSendToFinanceModal', () => {
      this.resetModal()
    })
  },
  methods: {
    closeModal() {
      this.$emit('close-finance-modal');
    },
    forward() {
      axios.post(`/kgs/send-to-finance/${this.$parent.ticketData.ticket.id}`, {
        to: this.to,
        description: this.description,
      }).then((response) => {
        this.closeModal();
        this.resetModal();
        EventBus.$emit('send_notification', 'replies',
            'Ticket Info', `Email has been sent successfully`, 'success');

        EventBus.$emit('status_updated', {
          'status': response.data.ticket.ticket.status,
          'status_id': response.data.ticket.ticket.status_id,
        })
      })
    },
    resetModal() {
      this.to = [];
      this.description = '';
    }
  },
  computed: {
    can_send() {
      return this.description != '' && this.to.length;
    },
    submitClass() {
      if (this.can_send) {
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