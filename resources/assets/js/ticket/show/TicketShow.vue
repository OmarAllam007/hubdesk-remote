<template>
  <div>
    <notifications-component></notifications-component>

    <div v-if="!authorizations.display_ticket">
      <div class="flex w-full">
          <div class="flex  justify-center">
            <div class="p-5 m-5 rounded-2xl bg-orange-300 shadow-lg   w-1/2 ">
              <strong class="text-2xl flex justify-center ">
                <i class="fa fa-lock pl-2  pr-2 pt-1 "></i>
                {{ $root.t('You are not authorized to display the ticket.') }}
              </strong>
            </div>
          </div>
        </div>
    </div>

    <div v-if="authorizations.display_ticket">
      <div class="flex m-5 ">
        <div class="w-full ">
          <button
              class="flex-grow bg-gray-600    hover:bg-viola  text-white font-bold py-2 px-4  hover:text-white rounded-xl  mr-2 shadow-md pb-2 mb-2 "
              @click="showReassignModal" v-if="authorizations.reassign">
            <i class="fa fa-mail-forward"></i>
            {{ $root.t('Re-assign') }}
          </button>

          <button
              class="flex-grow bg-gray-600    hover:bg-viola  text-white font-bold py-2 px-4  hover:text-white rounded-xl  mr-2 shadow-md "
              @click="showForwardModal" v-if="authorizations.forward">
            <i class="fa fa-arrow-circle-right"></i>
            {{ $root.t('Forward') }}
          </button>

          <button v-if="!data.ticket.is_task && data.ticket.is_support"
                  class="flex-grow bg-gray-600    hover:bg-viola  text-white font-bold py-2 px-4  hover:text-white rounded-xl  mr-2 shadow-md "
                  @click="showDuplicateModal">
            <i class="fa fa-copy"></i>
            {{ $root.t('Duplicate') }}
          </button>

          <button @click="printTicket"
                  class="flex-grow bg-gray-600    hover:bg-viola  text-white font-bold py-2 px-4  hover:text-white rounded-xl  mr-2 shadow-md ">
            <i class="fa fa-print"></i>
            {{ $root.t('Print') }}
          </button>

          <button v-if="authorizations.send_to_finance"
                  class="flex-grow bg-gray-600    hover:bg-viola  text-white font-bold py-2 px-4  hover:text-white rounded-xl  mr-2 shadow-md"
                  @click="showFinanceModal"
          >
            <i class="fa fa-money"></i>
            {{ $root.t('Send to finance') }}
          </button>

          <button
              class="flex-grow bg-gray-600    hover:bg-viola  text-white font-bold py-2 px-4  hover:text-white rounded-xl  mr-2 shadow-md"
              @click="showComplaintModal" v-if="authorizations.send_complaint">
            <i class="fa fa-comments "></i>
            {{ $root.t('Complaint') }}
          </button>
        </div>
      </div>
      <div class="flex justify-between">
        <div class="flex flex-col">
          <p class="ml-3 mr-3  pt-3 pl-4 pr-4 pb-1 text-2xl font-bold text-3xl">
            #{{ data.ticket.id }} - {{ data.requester.employee_id }} - <span v-html="data.ticket.subject"></span></p>
          <div class="flex pt-5 ">
            <div class="w-full">
              <strong class="pl-4 pr-4 m-3">{{ $root.t('By') }} : {{ data.ticket.requester }}</strong>
              <strong class="pl-4 pr-4 m-3">{{ $root.t('Created at') }} : {{ data.ticket.created_at }}</strong>
              <strong class="pl-4 pr-4 m-3" v-if="data.ticket.due_date">{{ $root.t('Due date') }} :
                {{ $root.t(data.ticket.due_date) }}</strong>

              <strong class="pl-4 pr-4 m-3" v-if="data.ticket.close_date">{{ $root.t('Closed Date') }} :
                {{ data.ticket.close_date }}</strong>

            </div>
          </div>
          <div class="flex" v-if="data.ticket.is_duplicated">
            <strong class="pl-4 pr-4 m-3">{{ $root.t('Duplicated Request from') }}
              # <a target="_blank" :href="`/ticket/${data.ticket.request_id}`">{{ data.ticket.request_id }}</a>
            </strong>
          </div>

          <div class="flex" v-if="data.ticket.is_task">
            <strong class="pl-4 pr-4 m-3">{{ $root.t('Main Request') }}
              # <a target="_blank" :href="`/ticket/${data.ticket.request_id}`">{{ data.ticket.request_id }}</a>
            </strong>
          </div>
        </div>

        <div class="m-3 pt-3   pb-1 rounded-lg  ">
          <div class="flex flex-col  text-md ">
            <div class="flex justify-between">
              <p :class="getStatusColor" class="p-3 pl-5 pr-5  rounded-full shadow-md">{{ $root.t(status_name) }}</p>
            </div>
          </div>
        </div>
      </div>
      <div class="flex justify-center pt-5 ">
        <div class="w-full md:w-full  lg:w-full  xl:full  2xl:w-2/3 ">
          <div class="tabUI flex flex-row justify-center">
            <button class="w-1/6 bg-white rounded-xl p-3 m-3 tabUIButton border"
                    :class="{tabUIButtonSelected : selectedTab == 0}" @click="changeTab(0)"><i class="fa fa-ticket"></i>
              {{ $root.t('Ticket') }}
            </button>
            <button class="w-1/6 bg-white rounded-xl p-3 m-3 tabUIButton border"
                    :class="{tabUIButtonSelected : selectedTab == 1}" @click="changeTab(1)"><i
                class="fa fa-comments-o"></i> {{ $root.t('Conversation') }}
            </button>
            <button class="w-1/6 bg-white rounded-xl p-3 m-3 tabUIButton border" v-if="authorizations.is_support"
                    :class="{tabUIButtonSelected : selectedTab == 2}" @click="changeTab(2)"><i class="fa fa-tasks"></i>
              {{ $root.t('Tasks') }}
            </button>
            <button class="w-1/6 bg-white rounded-xl p-3 m-3 tabUIButton border" v-if="authorizations.is_support"
                    :class="{tabUIButtonSelected : selectedTab == 3}" @click="changeTab(3)"><i
                class="fa fa-support"></i> {{ $root.t('Resolution') }}
            </button>
            <button class="w-1/6 bg-white rounded-xl p-3 m-3 tabUIButton border"
                    :class="{tabUIButtonSelected : selectedTab == 4}" @click="changeTab(4)"><i
                class="fa fa-check"></i> {{ $root.t('Approvals') }}
            </button>
            <button class="w-1/6 bg-white rounded-xl p-3 m-3 tabUIButton border"
                    :class="{tabUIButtonSelected : selectedTab == 5}" @click="changeTab(5)">
              <i class="fa fa-history"></i> {{ $root.t('Ticket Log') }}
            </button>

            <button class="w-1/6 bg-white rounded-xl p-3 m-3 tabUIButton border"
                    v-if="ticketData.ticket.attachments.length"
                    :class="{tabUIButtonSelected : selectedTab == 6}" @click="changeTab(6)">
              <i class="fa fa-files-o"></i> {{ $root.t('Attachments') }}
            </button>
          </div>
        </div>
      </div>

      <loader v-if="loading"></loader>

      <div class="w-full" v-else>
        <ticket-main :ticket="ticketData.ticket" v-show="selectedTab == 0"></ticket-main>
        <ticket-conversation v-show="selectedTab == 1" :ticket="ticketData.ticket"></ticket-conversation>
        <tasks v-show="selectedTab == 2" :ticket="ticketData.ticket"></tasks>
        <resolution v-if="selectedTab == 3" v-cloak></resolution>

        <approvals v-show="selectedTab == 4" :ticket_id="data.ticket.id"
                   :is_task="ticketData.ticket.is_task"
                   :approvals="ticketData.ticket.approvals"
                   :task_approvals="ticketData.ticket.task_approvals"
                   :submit_approval="ticketData.ticket.authorizations.submit_approval"
                   :templates="templates"
        ></approvals>

        <ticket-log :ticket_id="ticketData.ticket.id" v-show="selectedTab == 5"></ticket-log>
        <ticket-attachments :files="ticketData.ticket.attachments" v-show="selectedTab == 6"></ticket-attachments>


        <reassign :isReassignOpened="reassignModalOpened"
                  @close-reassign-modal="closeReassignModal()"
                  v-show="reassignModalOpened"></reassign>

        <forward :isForwardOpened="forwardModalOpened"
                 @close-forward-modal="closeForwardModal()"
                 v-if="forwardModalOpened"></forward>

        <duplicate
            :is-duplicate-opened="duplicateModalOpened"
            @close-duplicate-modal="closeDuplicateModal()"
            v-if="duplicateModalOpened"
        ></duplicate>
        <send-to-finance
            :is-finance-opened="financeModalOpened"
            @close-finance-modal="closeFinanceModal()"
            v-if="financeModalOpened"
        ></send-to-finance>
        <complaint
            :is-complaint-opened="complaintModalOpened"
            @close-complaint-modal="closeComplaintModal()"
            v-if="complaintModalOpened"
        ></complaint>


      </div>
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
import TicketLog from "../log/TicketLog";
import {EventBus} from "../../EventBus";
import Loader from "../_components/Loader";
import Reassign from "../actions/Reassign";
import Forward from "../actions/Forward";
import axios from 'axios';
import Duplicate from "../actions/Duplicate";
import SendToFinance from "../actions/SendToFinance";
import Complaint from "../actions/Complaint";
import TicketAttachments from "../attachments/TicketAttachments";

export default {
  name: "TicketShow",
  props: ['data', 'translations'],
  data() {
    var ticketData = '';

    if (this.data) {
      ticketData = this.data;
    }

    return {
      ticketData,
      authorizations: ticketData.ticket.authorizations,
      status_name: '',
      status_id: '',
      selectedTab: 0,
      templates: {},
      loading: '',
      reassignModalOpened: false,
      forwardModalOpened: false,
      duplicateModalOpened: false,
      financeModalOpened: false,
      complaintModalOpened: false,
      users: []
    }
  },

  created() {
    this.status_id = this.data.ticket.status_id
    this.status_name = this.data.ticket.status
    this.$root.translations = this.translations;

    EventBus.$on('status_updated', (reply) => {
      this.status_name = reply.status;
      this.status_id = reply.status_id;
    });

    this.loading = true;
    axios.get('/list/approvers').then((resposne) => {
      this.approvers = resposne.data
      this.loading = false;
    })


  },
  methods: {
    changeTab(index) {
      this.selectedTab = index;
    },
    showReassignModal() {
      this.reassignModalOpened = true;
    },
    showDuplicateModal() {
      this.duplicateModalOpened = true;
    },

    showFinanceModal() {
      this.financeModalOpened = true;
    },

    showForwardModal() {
      this.forwardModalOpened = true;
      EventBus.$emit('openForwardModal');
    },

    showComplaintModal() {
      this.complaintModalOpened = true;
    },
    closeReassignModal() {
      this.reassignModalOpened = false;
    },
    closeForwardModal() {
      this.forwardModalOpened = false;
    },
    closeDuplicateModal() {
      this.duplicateModalOpened = false;
    },
    closeFinanceModal() {
      this.financeModalOpened = false;
    },
    closeComplaintModal() {
      this.complaintModalOpened = false;
    },
    printTicket() {
      window.open(`/ticket/print/${this.ticketData.ticket.id}`, '_blank');
    }
  },

  computed: {
    getStatusColor() {
      var open = [1, 2, 3];
      var pending = [4, 5, 6];
      var closed = [7, 8, 9];
      if (open.indexOf(parseInt(this.status_id)) != -1) {
        return 'bg-gray-600 text-white';
      } else if (pending.indexOf(parseInt(this.status_id)) != -1) {
        return 'bg-yellow-700 text-white';
      } else {
        return 'bg-green-700 text-white';
      }
    }
  },
  components: {
    TicketAttachments,
    Complaint,
    SendToFinance,
    Duplicate,
    Forward,
    Reassign,
    Loader, TicketLog, Approvals, Resolution, TicketMain, TicketConversation, Tasks, NotificationsComponent
  }
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