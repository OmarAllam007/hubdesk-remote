<template>
  <div v-if="isComplaintOpened">
    <transition name="forward-modal">
      <div class="modal-mask ">
        <div class="modal-wrapper">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" @click="closeModal()"><i class="fa  fa-times"></i></button>
                <h3 class="modal-title text-2xl ">{{ $root.t('Complaint') }}</h3>
              </div>
              <div class="modal-body">
                <div class="flex">
                  <div class="w-full">
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
       }"

                      ></editor>
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button
                    :class="submitClass"
                    class="text-white font-bold py-2 px-4  hover:text-white rounded-xl  mr-2 shadow-md pb-2 mb-2 "
                    @click="complaint"
                    :disabled="!can_complaint">
                  <i class="fa fa-send"></i>
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
  name: "Complaint",
  components: {Loader, vSelect, Editor},

  props: ["isComplaintOpened"],
  data() {
    return {
      description: ''
    }
  },
  methods: {
    closeModal() {
      this.$emit('close-complaint-modal');
    },
    complaint() {
      axios.post(`/ticket/complaint/${this.$parent.ticketData.ticket.id}`, {
        description: this.description,
      }).then((response) => {
        EventBus.$emit('add_to_replies', {'reply': response.data.reply});

        this.closeModal();
        this.description = '';
        EventBus.$emit('send_notification', 'replies',
            'Ticket Info', `Complaint has been sent successfully`, 'success');
      })
    },
  },
  computed: {
    can_complaint() {
      return this.description != '';
    },
    submitClass() {
      if (this.can_complaint) {
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