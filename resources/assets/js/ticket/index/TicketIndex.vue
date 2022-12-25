<template>
  <div class="flex flex-col">
    <div class="flex justify-center" v-if="initLoading">
      <div class="loader ease-linear rounded-full border-4 border-t-4 border-gray-200 h-24 w-24 mt-64"></div>
    </div>
    <div class="flex w-full pt-5 justify-center" v-if="!loading || !initLoading">
      <div class="w-3/12  justify-start ml-5">
        <div class="flex">
          <button
              class=" bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 h-16 w-16
          px-4 border border-blue-500 hover:border-transparent rounded collapse-btn" v-if="scopes.length"
              @click="sidebar_visibility = !sidebar_visibility">
            <i class="fa fa-bars transform "
               :class="{'rotate-90': sidebar_visibility , 'animate-pulse':!sidebar_visibility}"></i>

          </button>
          <div class="bg-transparent text-blue-700 font-semibold hover:text-white py-2
          px-4  rounded scopeArea  pt-4 ">
            <p v-text="selected_scope_str">
            </p>
          </div>
        </div>
      </div>

      <div class="w-8/12 flex justify-end pr-3">
        <button @click="loadTickets" class="h-16 px-6  searchbtn rounded-full text-white mr-2 shadow-lg"><i
            class="fa fa-search"></i>
        </button>

        <input v-model="search" v-on:keyup.enter="loadTickets" class="w-1/2 h-16 px-3  rounded-3xl mb-8 focus:outline-none focus:shadow-outline border-0
      text-xl px-8 shadow-lg" type="search" placeholder="Ticket ID / Employee ID">

        <button @click="toggleAdvancedFilter" class="h-16 px-6  searchbtn rounded-full text-white ml-2 shadow-lg">
          <i :class="advancedFilter"></i>
          <!--          <span class="sm:hidden">{{ advanced_filter ? 'Hide Search' : 'Advanced Search' }}</span>-->
        </button>
      </div>
    </div>

    <transition name="slide-fade">
      <div class="w-full flex flex-col p-4" v-show="advanced_filter">
        <criteria ref="criteria" @setCriterionValue="selectValues"></criteria>
        <div class="w-full">
          <button @click="applyAdvancedFilter" class="bg-transparent hover:bg-green-500 text-green-700 font-semibold hover:text-white py-2 h-16
              px-6 border border-green-500 hover:border-transparent rounded"><i class="fa fa-filter"></i> Filter
          </button>
          <button :disabled="canFilter" @click="clearFilter" class="bg-transparent hover:bg-gray-500 text-gray-700 font-semibold hover:text-white py-2 h-16
              px-6 border border-gray-500 hover:border-transparent rounded"><i class="fa fa-times"></i> Clear
          </button>
        </div>
      </div>
    </transition>

    <div class="w-full  p-1  " v-if="!loading">
      <div class="flex justify-end">


        <!--        <div class="flex ">-->

        <!--        </div>-->
      </div>

    </div>
    <!--    sidebar -->
    <div class="w-full  p-3 flex flex-col md:flex-row xl:flex-row lg:flex-row 2xl:flex-row">
      <transition name="slide-fade">
        <div class="w-full md:w-3/12 xl:w-3/12 lg:w-3/12 2xl:w-3/12  h-full" v-show="sidebar_visibility">
          <div class="flex-col rounded-xl  bg-white shadow mr-0 md:mr-3 xl:mr-3 lg:mr-3 2xl:mr-3"
               v-if="scopes.length">
            <filters :scopes="scopes" :total="tickets.total" :services="services"></filters>
          </div>
        </div>
      </transition>


      <div class="relative" :class="ticketsWidth">
        <div v-if="!loading && !initLoading" class="flex py-5 justify-between items-end">
          <div>
            <button class="h-12 px-16 bg-gray-500 rounded-2xl text-white mr-2 shadow-lg"
                    :class="{'searchbtn':selectedTicketType == 0}" @click="changeTicketType(0)"> All
            </button>
            <button class="h-12 px-16 bg-gray-500 rounded-2xl text-white mr-2 shadow-lg"
                    :class="{'searchbtn':selectedTicketType == 1}" @click="changeTicketType(1)"> Ticket
            </button>
            <button class="h-12  px-16 bg-gray-500 rounded-2xl  text-white mr-2 shadow-lg"
                    :class="{'searchbtn':selectedTicketType == 2}" @click="changeTicketType(2)"> Task
            </button>

          </div>

          <div>
            <recent-tickets :tickets="lastTickets"></recent-tickets>
          </div>
        </div>
        <loader v-if="loading && !initLoading"></loader>
        <div class="transition flex flex-col ease-in-out mt-3 md:mt-0 xl:mt-0 lg:mt-0 2xl:mt-0" v-else>
          <div v-if="!loading && !tickets.data.length" class="flex justify-center pt-10">
            <div class="p-4 w-2/3  bg-blue-600 font-bold text-white rounded-2xl text-center shadow-md">
              <p>No Tickets Found!</p>
            </div>
          </div>

          <div v-for="ticket in tickets.data" v-else>

            <ticket :ticket="ticket"></ticket>
          </div>
          <div class="flex justify-center">
            <pagination :pagination="tickets" @paginate="loadTickets(false)"></pagination>
          </div>
        </div>

        <div v-else class="flex justify-center pt-10">
          <div class="p-4 w-1/2 bg-blue-600  font-bold text-white rounded-2xl text-center shadow-md">
            <p>No Tickets Found!</p>
          </div>
        </div>
      </div>
    </div>


    <div class="modal fade" id="serviceModal" tabindex="-1" role="dialog" aria-labelledby="serviceModal">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title" id="serviceModal">Filter By Service</h4>
          </div>
          <div class="modal-body">
            <select name="technicians" multiple class="form-control" id="technicians" size="25"
                    v-model="selected_services">
              <option :value="service.id" v-for="service in services"> {{ service.name }}</option>
            </select>

          </div>
          <div class="modal-footer">
            <button type="button" class="p-3" data-dismiss="modal" id="closeTech">Close</button>
            <button type="button" class="p-3 bg-green-600 rounded text-white hover:bg-green-700"
                    id="chooseTech" @click="loadTickets"><i class="fa fa-filter"></i> Filter
            </button>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
import Pagination from './Pagination.vue';
import Ticket from './Ticket.vue';
import Filters from './Filters.vue';
import axios from 'axios';
import Criteria from "../../Criteria.vue";
import EventBus from "../../Bus";
import Loader from "../_components/Loader";
import _ from "lodash";
import RecentTickets from "./RecentTickets";

export default {
  name: "TicketIndex",
  props: ['last-tickets'],
  data() {
    return {

      loading: false,
      advanced_filter: false,
      initLoading: false,
      clear: false,
      criterions: [],
      scopes: {},
      requirements: [],
      services: [],
      selected_scope: '',
      selected_scope_str: '',
      sidebar_visibility: false,
      search: '',
      selectedTicketType: 0,
      tickets: {
        total: 0,
        per_page: 2,
        from: 1,
        to: 0,
        current_page: localStorage.getItem('page') ? localStorage.getItem('page') : 1
      },
      offset: 4,
      selected_services: []
    }
  },
  created() {
    console.log(sessionStorage.getItem('ticket_type'))
    this.initLoading = true;
    this.selected_scope = sessionStorage.getItem('scope') ? sessionStorage.getItem('scope') : '';
    this.selectedTicketType = localStorage.getItem('ticket_type') ? localStorage.getItem('ticket_type') : 0;
    this.loadTickets();
    // this.getSelectedScope();
  },

  mounted() {
    this.$on('changeScope', (scope) => {
      this.selected_scope = scope;
      this.tickets.current_page = 1;
      this.loadTickets();

    });

    this.$on('toggle-sidebar', (sidebar_visibility) => {
      this.sidebar_visibility = sidebar_visibility;
    });


  },
  computed: {

    advancedFilter() {
      return this.advanced_filter ? 'fa fa-search-minus' : 'fa fa-search-plus'
    },
    ticketsWidth() {
      return this.sidebar_visibility ? 'w-full md:10/12 xl:10/12 lg:10/12 2xl:10/12' : 'w-full';
    },
    canFilter() {
      if (!this.$refs.criteria) {
        return false;
      }
      return this.$refs.criteria.$data.requirements.length > 0;
    }

  },

  methods: {
    changeTicketType(type) {
      this.selectedTicketType = type;
      localStorage.setItem('ticket_type', this.selectedTicketType);
      this.loadTickets(false)
    },
    clearFilter() {
      this.$refs.criteria.$data.requirements = [];
      this.clear = true;
      this.criterions = [];
      this.loadTickets(true, true)
    },
    applyAdvancedFilter() {
      let requirements = [];

      this.$refs.criteria.$data.requirements.forEach((req, key) => {
        requirements.push({
          'field': req.field,
          'label': req.label,
          'value': req.value,
          'showMenuIcon': req.showMenuIcon,
          'operator': req.operator,
        })
      });

      this.criterions = requirements;
      this.loadTickets();
    },
    toggleAdvancedFilter() {
      this.advanced_filter = !this.advanced_filter;
    },
    selectValues() {
      // console.log('logged');
    },

    loadTickets(spin = true, clearFilter = false) {
      this.loading = spin;

      localStorage.setItem('page', this.tickets.current_page);
      // console.log(localStorage.getItem('page'))
      axios.post(`/ajax/ticket`, {
        'page': localStorage.getItem('page'),
        'scope': this.selected_scope,
        'search': this.search,
        'clear': this.clear,
        'criterions': this.criterions,
        'selected_services': this.selected_services,
        'ticket_type':this.selectedTicketType,
      }).then((response) => {
        if (response.data.ticket) {
          window.location.href = `/ticket/${this.search}`;
        } else {
          if (!response.data.tickets) {
            this.tickets = [];
          } else {
            this.tickets = response.data.tickets;
          }
          this.scopes = Object.keys(response.data.scopes).map((key) => [key, response.data.scopes[key]]);
          this.selected_scope = response.data.scope;
          this.criterions = response.data.criterions;
          this.loading = false;
          this.initLoading = false;
          this.total = response.total;
          this.services = response.data.sortedServices;
          this.getSelectedScope();
        }
      }).catch((e) => {
        console.log(e)
        this.loading = false;
      });
    },

    getSelectedScope() {
      // setTimeout(() => {
      //   console.log(this.scopes)

      for (let item in this.scopes) {
        if (this.scopes[item][0] == this.selected_scope) {
          this.selected_scope_str = this.scopes[item][1];
        }
      }
      // }, 1000)
    }
  },

  components: {RecentTickets, Pagination, Ticket, Filters, Criteria, Loader}
}
</script>

<style scoped>
.loader {
  border-top-color: #1a1d50;
  -webkit-animation: spinner 1.5s linear infinite;
  animation: spinner 1.5s linear infinite;
}

@-webkit-keyframes spinner {
  0% {
    -webkit-transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
  }
}

@keyframes spinner {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

.searchbtn {
  background: rgba(26, 29, 80, 0.9);
}

.scopeArea {
  color: rgba(26, 29, 80, 0.9);
}

.collapse-btn {
  color: rgba(26, 29, 80, 0.9);
  border-color: rgba(26, 29, 80, 0.9);
}

.collapse-btn:hover {
  background: rgba(26, 29, 80, 0.5);
}

.slide-fade-enter-active {
  transition: all .5s ease;
}

.slide-fade-leave-active {
  transition: all 0s cubic-bezier(1.0, 0.5, 0.8, 1.0);
}

.slide-fade-enter, .slide-fade-leave-to
  /* .slide-fade-leave-active below version 2.1.8 */
{
  transform: translateY(-10px);
  opacity: 0;
}
</style>