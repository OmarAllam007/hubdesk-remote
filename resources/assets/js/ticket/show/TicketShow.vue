<template>
  <div>
    <notifications-component></notifications-component>
    <p class="m-3 pt-3 pl-4 pr-4 pb-1 text-2xl font-bold text-3xl">
      #{{ data.ticket.id }} - {{ data.requester.employee_id }} - <span v-html="data.ticket.subject"></span></p>
    <div class="flex justify-center">
      <div class="w-1/2">
        <div class="tabUI flex flex-row justify-evenly">
          <button class="w-1/6 bg-white rounded-xl p-3 m-3 tabUIButton border"
                  :class="{tabUIButtonSelected : selectedTab == 0}" @click="changeTab(0)"><i class="fa fa-ticket"></i>
            Ticket
          </button>
          <button class="w-1/6 bg-white rounded-xl p-3 m-3 tabUIButton border"
                  :class="{tabUIButtonSelected : selectedTab == 1}" @click="changeTab(1)"><i
              class="fa fa-comments-o"></i> Conversation
          </button>
          <button class="w-1/6 bg-white rounded-xl p-3 m-3 tabUIButton border"
                  :class="{tabUIButtonSelected : selectedTab == 2}" @click="changeTab(2)"><i class="fa fa-tasks"></i>
            Tasks
          </button>
          <button class="w-1/6 bg-white rounded-xl p-3 m-3 tabUIButton border"
                  :class="{tabUIButtonSelected : selectedTab == 3}" @click="changeTab(3)"><i
              class="fa fa-support"></i> Resolution
          </button>
          <button class="w-1/6 bg-white rounded-xl p-3 m-3 tabUIButton border"
                  :class="{tabUIButtonSelected : selectedTab == 4}" @click="changeTab(4)"><i
              class="fa fa-check"></i> Approvals
          </button>
          <button class="w-1/6 bg-white rounded-xl p-3 m-3 tabUIButton border"
                  :class="{tabUIButtonSelected : selectedTab == 5}" @click="changeTab(5)"><i
              class="fa fa-history"></i> Ticket Log
          </button>
        </div>
      </div>
    </div>

    <div class="w-full">
      <ticket-main :ticket="this.data.ticket" v-show="selectedTab == 0"></ticket-main>
      <ticket-conversation v-show="selectedTab == 1" :ticket="this.data.ticket"></ticket-conversation>
      <tasks v-show="selectedTab == 2" :ticket="this.data.ticket"></tasks>
      <resolution v-show="selectedTab == 3"></resolution>

      <approvals v-show="selectedTab == 4"  :ticket_id="data.ticket.id"
                 :is_task="data.ticket.is_task"
                 :approvals="data.ticket.approvals"
                 :task_approvals="data.ticket.task_approvals"
                 :submit_approval="data.ticket.authorizations.submit_approval"
                 :templates="templates"
      ></approvals>
    </div>
  </div>


</template>

<script>
import TicketMain from "./TicketMain";
import TicketConversation from "../conversation/TicketConversation";
import Tasks from "../tasks/Tasks";
import NotificationsComponent from "./NotificationsComponent";
import Resolution from "../resolution/Resolution";
import Approvals from "../approvals/Approvals";

export default {
  name: "TicketShow",
  props: ['data'],
  data() {
    return {
      selectedTab: 4 ,
      templates: {},
    }
  },
  methods: {
    changeTab(index) {
      this.selectedTab = index;
    }
  },

  components: {Approvals, Resolution, TicketMain, TicketConversation, Tasks, NotificationsComponent}
}
</script>

<style scoped>
.tabUIButtonSelected {
  background: rgba(26, 29, 80, 0.9);
  color: white;
}

.tabUIButton:hover {
  background: rgba(26, 29, 80, 0.9);
  color: white;
}
</style>