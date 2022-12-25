<template>
  <div>
    <div>
      <button class="h-16 px-6  searchbtn rounded-2xl  text-white mr-2 shadow-lg" @click="showRecentModal"><i
          class="fa fa-history" ></i>
      </button>
    </div>

    <div v-if="modalOpened">
      <transition name="modal">
        <div class="modal-mask ">
          <div class="modal-wrapper">
            <div class="modal-dialog modal-sm  ">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" @click="closeModal()"><i class="fa  fa-times"></i></button>
                  <h3 class="modal-title text-2xl ">{{ 'Recent Tickets' }}</h3>
                </div>
                <div class="modal-body">
                  <div v-if="tickets.length != 0"  v-for="ticket in tickets" >
                    <a
                        :href="`/ticket/${ticket}`" target="_blank" class="flex p-5 m-2 rounded-2xl  bg-gray-200 "
                    >#{{ ticket }}</a>
                  </div>
                  <div v-if="tickets.length == 0">
                    <p class="p-5 m-2 rounded-2xl  bg-yellow-200">No History found!</p>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button"
                          class="bg-white hover:text-gray-800    font-bold py-2 px-4  hover:text-white rounded-xl  mr-2 pb-2 mb-2 "
                          data-dismiss="modal" @click="closeModal()">
                   Close
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </transition>
    </div>
  </div>
</template>

<script>
import Ticket from "./Ticket";
export default {
  name: "RecentTickets",
  props:['tickets'],
  components: {Ticket},
  data() {
    return {
      modalOpened: false,
    }
  },
  methods: {
    showRecentModal() {
      this.modalOpened = true;
    },
    closeModal() {
      this.modalOpened = false;
    }
  }
}
</script>

<style scoped>
.searchbtn {
  background: rgba(26, 29, 80, 0.9);
}

.modal-mask {
  position: fixed;
  z-index: 9998;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, .5);
  display: table;
  transition: opacity .3s ease;
}

.modal-wrapper {
  display: table-cell;
  vertical-align: middle;
}
</style>