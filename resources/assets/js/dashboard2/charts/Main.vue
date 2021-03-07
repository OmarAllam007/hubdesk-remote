<template>
  <div v-if="loading">
    <i class="fa fa-spin fa-spinner fa-2x"></i>
  </div>
  <div v-else>
    <div class="flex">
      <div class="flex flex-col w-2/12 bg-white shadow-md p-5 mr-5 rounded-xl ">
        <p class="text-2xl font-bold"><i class="fa fa-filter"></i> Filters</p>
        <div class="pt-5"></div>
        <hr>
        <div class="pt-5"></div>

        <div class="flex flex-col">
          <p class="text-xl font-bold">Created Date</p>
          <div class="pt-5"></div>
          <label>
            From
            <input type="date" class="w-full pl-10 pr-3 py-2
          rounded-lg border-2 border-gray-200 outline-none focus:border-indigo-500">
          </label>
          <label>
            To
            <input type="date" class="w-full pl-10 pr-3 py-2
          rounded-lg border-2 border-gray-200 outline-none focus:border-indigo-500">
          </label>
        </div>

        <div class="flex flex-col">
          <p class="text-xl font-bold">Created Date</p>
          <div class="pt-5"></div>
          <label>
            From
            <input type="date" class="w-full pl-10 pr-3 py-2
          rounded-lg border-2 border-gray-200 outline-none focus:border-indigo-500">
          </label>
          <label>
            To
            <input type="date" class="w-full pl-10 pr-3 py-2
          rounded-lg border-2 border-gray-200 outline-none focus:border-indigo-500">
          </label>
        </div>

        <div class="pt-5"></div>
        <hr>
        <div class="pt-5"></div>

        <p class="text-xl font-bold">Business Unit</p>
        <div class="flex flex-col w-full ">
          <div class="p-5 hover:bg-yellow-100  rounded-2xl text-md" v-for="company in businessUnits">
            <label class="inline-flex items-center">
              <input type="checkbox" class="form-checkbox">
              <span class="ml-2 font-medium  ">{{ company.code + ' - ' + company.name }}</span>
            </label>
          </div>

        </div>


        <div class="w-full px-3 mb-5">
          <button class="block w-full bg-green-500 hover:bg-green-700 focus:bg-green-700
            text-white rounded-lg px-3 py-3 font-semibold"><i class="fa fa-filter"></i> Filter
          </button>
        </div>


        <!--        <label>-->
        <!--          Business Unit-->
        <!--          <select name="business_units" id="business_units" class="form-control select2">-->
        <!--            <option value="">1</option>-->
        <!--          </select>-->
        <!--        </label>-->
      </div>
      <div class="flex">
        <div class="flex flex-col w-10/12 ">
          <p class="text-3xl font-bold  pb-5 text-center">Tickets Overview</p>
          <p class="text-2xl font-bold pb-5">Tickets Created Vs. Closed</p>
          <tickets-created-vs-closed :ticketsCreatedClosed="ticketsCreatedClosed"></tickets-created-vs-closed>

          <p class="text-2xl  font-bold pb-5 pt-5 ">Tickets Priority - YTD</p>
          <ticket-priority :ticketsPriority="ticketsPriority"></ticket-priority>

          <p class="text-3xl font-bold  pb-5 pt-5 text-center">Open Tickets Analysis - YTD</p>
          <p class="text-2xl font-bold pb-5">Tickets Status Vs. Category</p>
          <ticket-status-vs-category :ticketStatus="ticketsStatus"></ticket-status-vs-category>
          <p class="text-2xl font-bold pb-5 pt-5 ">Tickets Priority Vs. Category</p>
          <ticket-priority-vs-category :ticketPriorityCategory="ticketPriorityCategory"></ticket-priority-vs-category>


          <p class="text-3xl font-bold  pb-5 pt-5 text-center">Closed Tickets Analysis - YTD</p>
          <p class="text-2xl font-bold pb-5">Tickets Status Vs. Category</p>
          <ticket-closed-status-vs-category
              :closedTicketsStatusVsCategory="closedTicketsStatusVsCategory"></ticket-closed-status-vs-category>
          <p class="text-2xl font-bold pb-5 pt-5">Tickets Priority Vs. Category</p>
          <closed-ticket-priority-vs-category
              :closedTicketPriorityCategory="closedTicketPriorityCategory"></closed-ticket-priority-vs-category>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import TicketPriority from "./TicketPriority";
import axios from 'axios';
import TicketsCreatedVsClosed from "./TicketsCreatedVsClosed";
import TicketStatusVsCategory from "./TicketStatusVsCategory";
import TicketPriorityVsCategory from "./TicketPriorityVsCategory";
import TicketClosedStatusVsCategory from "./TicketClosedStatusVsCategory";
import ClosedTicketPriorityVsCategory from "./ClosedTicketPriorityVsCategory";

export default {
  name: "Main",
  data() {
    return {
      ticketsPriority: [],
      ticketsCreatedClosed: [],
      ticketsStatus: [],
      ticketPriorityCategory: [],
      closedTicketsStatusVsCategory: [],
      loading: false,
      businessUnits: [],
    }
  },
  mounted() {
    $('.select2').select2({width: '100%', allowClear: true});
  },
  created() {
    this.loading = true;

    axios.get('/list/dashboard-business-unit').then((response) => {
      this.businessUnits = response.data;
    });

    axios.get('/dashboard/status-dashboard-data').then((response) => {
      this.ticketsPriority = response.data.ticketsPriority;
      this.ticketsCreatedClosed = response.data.ticketsCreatedClosed;
      this.ticketsStatus = response.data.ticketsStatusVsCategory;
      this.ticketPriorityCategory = response.data.ticketsPriorityVsCategory;
      this.closedTicketsStatusVsCategory = response.data.closedTicketsStatusVsCategory;
      this.closedTicketPriorityCategory = response.data.closedTicketsPriorityVsCategory;

      this.loading = false;
    }).catch(e => console.log(e));
  },
  components: {
    ClosedTicketPriorityVsCategory,
    TicketClosedStatusVsCategory,
    TicketPriorityVsCategory, TicketStatusVsCategory, TicketsCreatedVsClosed, TicketPriority
  }
}
</script>

<style scoped>

</style>