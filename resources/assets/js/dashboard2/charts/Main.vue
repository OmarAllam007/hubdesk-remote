<template>

  <div>
    <div class="flex flex-col md:flex-row lg-flex-row xl:flex-row ">
      <div
          class="h-full justify-start  w-full lg:w-2/12   md:w-2/12   xl:w-2/12   bg-white shadow-md p-5 mr-5 rounded-xl"
          id="filterBar">
        <p class="text-2xl font-bold"><i class="fa fa-filter"></i> Filters</p>
        <div class="pt-5"></div>
        <hr>
        <div class="pt-5"></div>

        <div class="flex flex-col">
          <p class="text-xl font-bold">Date</p>
          <div class="pt-5"></div>
          <label>
            From
            <input type="date" class="w-full pl-10 pr-3 py-2
          rounded-lg border-2 border-gray-200 outline-none focus:border-indigo-500" v-model="filters.date_from">
          </label>
          <label>
            To
            <input type="date" class="w-full pl-10 pr-3 py-2
          rounded-lg border-2 border-gray-200 outline-none focus:border-indigo-500" v-model="filters.date_to">
          </label>
        </div>

        <div class="pt-5"></div>
        <hr>
        <div class="pt-5"></div>

        <p class="text-xl font-bold">Business Unit</p>
        <div class="flex flex-col w-full ">
          <div class="p-5 hover:bg-yellow-100  rounded-2xl text-md" v-for="company in businessUnits">
            <label class="inline-flex items-center">
              <input type="checkbox" class="form-checkbox" @change="addToBusinessUnit(company.id)"
                     :checked="checkTheBox(company.id)">
              <span class="ml-2 text-md font-medium ">{{ company.code + ' - ' + company.name }}</span>
            </label>
          </div>

        </div>


        <div class="w-full px-3 mb-5">
          <button :class="getFilterClass" :disabled="!can_filter" @click="fillData"><i class="fa fa-filter"></i> Filter
          </button>
        </div>

        <div class="w-full px-3 mb-5">
          <button
              class="block w-full bg-red-500 hover:bg-red-700 focus:bg-red-700 text-white rounded-lg px-3 py-3 font-semibold"
              @click="clearFilters"><i class="fa fa-times"></i> Clear
          </button>
        </div>


        <!--        <label>-->
        <!--          Business Unit-->
        <!--          <select name="business_units" id="business_units" class="form-control select2">-->
        <!--            <option value="">1</option>-->
        <!--          </select>-->
        <!--        </label>-->
      </div>

      <div v-if="loading">
        <i class="fa fa-spin fa-spinner fa-2x"></i>
      </div>
      <div
          v-else
          class="h-full justify-start  w-full lg:w-10/12  md:w-10/12  xl:w-10/12  p-5 mr-5 rounded-xl print:w-full "
      >

        <div class="page-break">
          <div class="flex flex-col">
            <p class="text-3xl font-bold  pb-5 text-center">Tickets Overview</p>
            <p class="text-2xl font-bold pb-5">Tickets Created Vs. Closed</p>
            <tickets-created-vs-closed :ticketsCreatedClosed="ticketsCreatedClosed"></tickets-created-vs-closed>
          </div>
        </div>
        <div class="page-break">
          <div class="flex flex-col">
            <p class="text-2xl  font-bold pb-5 pt-5 ">Tickets Priority - YTD</p>
            <ticket-priority :ticketsPriority="ticketsPriority"></ticket-priority>
          </div>
        </div>
        <div class="page-break">
          <div class="flex flex-col">
            <p class="text-3xl font-bold  pb-5 pt-5 text-center">Open Tickets Analysis - YTD</p>
            <p class="text-2xl font-bold pb-5">Tickets Status Vs. Category</p>
            <ticket-status-vs-category :ticketStatus="ticketsStatus"></ticket-status-vs-category>
          </div>
        </div>

        <div class="page-break">
          <div class="flex flex-col">
            <p class="text-2xl font-bold pb-5 pt-5 ">Tickets Priority Vs. Category</p>
            <ticket-priority-vs-category :ticketPriorityCategory="ticketPriorityCategory"></ticket-priority-vs-category>
          </div>
        </div>


        <div class="page-break">
          <div class="flex flex-col">
            <p class="text-3xl font-bold  pb-5 pt-5 text-center">Closed Tickets Analysis - YTD</p>
            <p class="text-2xl font-bold pb-5">Tickets Status Vs. Category</p>
            <ticket-closed-status-vs-category
                :closedTicketsStatusVsCategory="closedTicketsStatusVsCategory"></ticket-closed-status-vs-category>
          </div>
        </div>


        <div class="page-break">
          <div class="flex flex-col">
            <p class="text-2xl font-bold pb-5 pt-5">Tickets Priority Vs. Category</p>
            <closed-ticket-priority-vs-category
                :closedTicketPriorityCategory="closedTicketPriorityCategory"></closed-ticket-priority-vs-category>
          </div>
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
import _ from 'lodash';
import {stringify} from "querystring";


export default {
  name: "Main",
  props: ['data','business'],

  data() {

    return {
      ticketsPriority: [],
      ticketsCreatedClosed: [],
      ticketsStatus: [],
      ticketPriorityCategory: [],
      closedTicketsStatusVsCategory: [],
      loading: false,
      businessUnits: [],
      filters: {
        date_from: '',
        date_to: '',
        business_units: [],
      }
    }
  },
  mounted() {
    $('.select2').select2({width: '100%', allowClear: true});
  },
  created() {
    this.loading = false;
    axios.get('/list/dashboard-business-unit').then((response) => {
      this.businessUnits = response.data;
    });

    this.ticketsPriority = this.data.ticketsPriority;
    this.ticketsCreatedClosed = this.data.ticketsCreatedClosed;
    this.ticketsStatus = this.data.ticketsStatusVsCategory;
    this.ticketPriorityCategory = this.data.ticketsPriorityVsCategory;
    this.closedTicketsStatusVsCategory = this.data.closedTicketsStatusVsCategory;
    this.closedTicketPriorityCategory = this.data.closedTicketsPriorityVsCategory;
  },
  methods: {

    fillData(loading = true) {
      this.loading = loading;


      axios.get('/dashboard/status-dashboard-data', {
        params: {
          filters: this.filters,
          businessUnit: this.business,
          paramsSerializer: params => {
            return stringify(params)
          }
        },
      }).then((response) => {
        this.ticketsPriority = response.data.ticketsPriority;
        this.ticketsCreatedClosed = response.data.ticketsCreatedClosed;
        this.ticketsStatus = response.data.ticketsStatusVsCategory;
        this.ticketPriorityCategory = response.data.ticketsPriorityVsCategory;
        this.closedTicketsStatusVsCategory = response.data.closedTicketsStatusVsCategory;
        this.closedTicketPriorityCategory = response.data.closedTicketsPriorityVsCategory;

        this.loading = false;
      }).catch(e => console.log(e));
    },
    addToBusinessUnit(id) {
      if (!this.filters.business_units.includes(id)) {
        this.filters.business_units.push(id);
      } else {
        const index = this.filters.business_units.indexOf(id);
        if (index > -1) {
          this.filters.business_units.splice(index, 1);
        }
      }
    },
    checkTheBox(id) {
      return _.includes(this.filters.business_units, id);
    },
    clearFilters() {
      this.filters = {
        date_from: '',
        date_to: '',
        business_units: [],
      }
      this.fillData(true);
    }
  },
  computed: {
    getFilterClass() {
      if (this.can_filter) {
        return 'block w-full bg-green-500 hover:bg-green-700 focus:bg-green-700 text-white rounded-lg px-3 py-3 font-semibold';
      }

      return 'block w-full bg-gray-500 hover:bg-gray-700 focus:bg-gray-700 text-white rounded-lg px-3 py-3 font-semibold';
    },
    can_filter() {
      return this.filters.date_from != '' || this.filters.date_to != ''
          || this.filters.business_units.length > 0;
    }
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