<template>
  <div class="flex flex-col">
    <div class="flex justify-center" v-if="initLoading">
      <div class="loader ease-linear rounded-full border-4 border-t-4 border-gray-200 h-24 w-24 mt-64"></div>
    </div>
    <div class="flex w-full pt-5 justify-center" v-if="!loading || !initLoading">
      <div class="w-4/12 flex justify-start ml-5">
        <button
            class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 h-16 w-16
          px-4 border border-blue-500 hover:border-transparent rounded collapse-btn" v-if="scopes.length"
            @click="sidebar_visibility = !sidebar_visibility">
          <i class="fa fa-bars"></i>
        </button>
      </div>

      <div class="w-8/12">
        <button @click="loadTickets" class="h-16 px-6  searchbtn rounded-full text-white mr-2 shadow-lg"><i
            class="fa fa-search"></i>
        </button>

        <input v-model="search" v-on:keyup.enter="loadTickets" class="w-1/2 h-16 px-3  rounded-3xl mb-8 focus:outline-none focus:shadow-outline border-0
      text-xl px-8 shadow-lg" type="search" placeholder="Ticket ID / Employee ID">

        <button @click="toggleAdvancedFilter" class="h-16 px-6  searchbtn rounded-full text-white ml-2 shadow-lg"><i
            class="fa fa-search-plus"></i> Advanced Search
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

    <div class="w-full flex p-3">
      <transition name="slide-fade">
        <div class="flex  w-3/12 h-full" v-show="sidebar_visibility">
          <div class="flex flex-col m-3 rounded-xl  bg-white shadow"
               v-if="scopes.length">
            <filters :scopes="scopes" :total="tickets.total"></filters>
          </div>
        </div>
      </transition>
      <div class="m-3 relative" :class="ticketsWidth">
        <div class="flex justify-center" v-if="loading && !initLoading">
          <div class="loader ease-linear rounded-full border-4 border-t-4 border-gray-200 h-24 w-24 mt-64"></div>
        </div>


        <div class="transition flex flex-col ease-in-out" v-else>
          <div  v-if="!loading && !tickets.data.length" class="flex justify-center pt-10">
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

        <div  v-else class="flex justify-center pt-10">
          <div class="p-4 w-1/2 bg-blue-600  font-bold text-white rounded-2xl text-center shadow-md">
            <p>No Tickets Found!</p>
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

export default {
  name: "TicketIndex",

  created() {
    this.initLoading = true;
    this.selected_scope = sessionStorage.getItem('scope') ? sessionStorage.getItem('scope') : '';
    this.loadTickets();
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

    EventBus.$on('setCriterionValue', (i, j, k) => {

    })
  },
  computed: {
    ticketsWidth() {
      return this.sidebar_visibility ? 'w-10/12' : 'w-full';
    },
    canFilter() {
      if (!this.$refs.criteria) {
        return false;
      }
      return this.$refs.criteria.$data.requirements.length > 0;
    }

  },
  data() {
    return {
      loading: false,
      advanced_filter: false,
      initLoading: false,
      criterions: [],
      scopes: {},
      requirements: [],
      selected_scope: '',
      sidebar_visibility: false,
      search: '',
      tickets: {
        total: 0,
        per_page: 2,
        from: 1,
        to: 0,
        current_page: 1
      },
      offset: 4,
    }
  },
  methods: {
    clearFilter() {
      this.$refs.criteria.$data.requirements = [];
      this.criterions = [];
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
      console.log('logged');
    },

    loadTickets(spin = true) {
      this.loading = spin;

      axios.post(`/api/ticket`, {
        'page': this.tickets.current_page,
        'scope': this.selected_scope,
        'search': this.search,
        'criterions': this.criterions,
      }).then((response) => {
        if (response.data.ticket) {
          window.location.href = `/ticket/${this.search}`;
        } else {
          this.tickets = response.data.tickets;
          this.scopes = Object.keys(response.data.scopes).map((key) => [key, response.data.scopes[key]]);
          this.selected_scope = response.data.scope;
          this.criterions = response.data.criterions;
          this.loading = false;
          this.initLoading = false;
          this.total = response.total;
        }
      }).catch(e => {
        this.loading = false;
      });
    },
  },
  components: {Pagination, Ticket, Filters, Criteria}
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