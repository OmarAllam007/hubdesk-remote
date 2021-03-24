<template>
  <div v-if="isDuplicateOpened">
    <transition name="forward-modal">
      <div class="modal-mask ">
        <div class="modal-wrapper">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" @click="closeModal()"><i class="fa  fa-times"></i></button>
                <h3 class="modal-title text-2xl "><i class="fa fa-copy"></i> {{ $root.t('Duplicate Ticket') }}</h3>
              </div>
              <div class="modal-body">
                <label>{{ $root.t('Number of duplicated tickets') }} : </label>
                <input type="number" name="name" id="name" placeholder="1" required
                       class="w-full px-3 py-2 placeholder-gray-300 border border-gray-300 rounded-md focus:outline-none
                       focus:ring focus:ring-indigo-100 focus:border-indigo-300 dark:bg-gray-700 dark:text-white
                       dark:placeholder-gray-500 dark:border-gray-600 dark:focus:ring-gray-900 dark:focus:border-gray-500"
                       value="1" autocomplete="off" v-model="number_of_tickets"/>

              </div>
              <div class="modal-footer">
                <button
                    :class="submitClass"
                    class="text-white font-bold py-2 px-4  hover:text-white rounded-xl  mr-2 shadow-md pb-2 mb-2 "
                    @click="duplicate"
                    :disabled="!can_duplicate">
                  <i class="fa fa-check-circle"></i>
                  {{ $root.t('Duplicate') }}
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

export default {
  name: "Duplicate",
  props: ["isDuplicateOpened"],
  data() {
    return {
      number_of_tickets: 1
    }
  },
  methods: {
    closeModal() {
      this.$emit('close-duplicate-modal');
    },
    duplicate() {
      axios.get(`duplicate/${this.$parent.ticketData.ticket.id}`, {
        params: {
          tickets_count: this.number_of_tickets
        }
      }).then((response) => {
        this.number_of_tickets = 1;
        this.closeModal();
        EventBus.$emit('send_notification', 'replies',
            'Ticket Info', `Ticket has been duplicated successfully`, 'success');

        if (response.data.ticket) {
          var win = window.open(`/ticket/${response.data.ticket.id}`, '_blank');
          win.focus();
        }

      })
    }
  },
  computed: {
    can_duplicate() {
      return this.number_of_tickets > 0 && this.number_of_tickets <= 10;
    },
    submitClass() {
      if (this.can_duplicate) {
        return 'bg-green-600 hover:bg-green-800 cursor-pointer';
      }
      return 'bg-gray-500 hover:bg-gray-500 cursor-not-allowed'
    }
  },
  watch: {
    number_of_tickets() {
      if (this.number_of_tickets <= 0) {
        this.number_of_tickets = 1;
      }
    }
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