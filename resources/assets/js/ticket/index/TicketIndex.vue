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
        <button @click="loadTickets"  class="h-16 px-6  searchbtn rounded-full text-white mr-2 shadow-lg"><i class="fa fa-search"></i>
        </button>
        <input v-model="search" class="w-1/2 h-16 px-3  rounded-3xl mb-8 focus:outline-none focus:shadow-outline border-0
      text-xl px-8 shadow-lg" type="search" placeholder="Ticket ID / Employee ID">
      </div>
    </div>
    <div class="w-full flex p-3">

      <transition name="slide-fade">
        <div class="flex  w-3/12 h-full" v-show="sidebar_visibility">
          <div class="flex flex-col m-3 rounded-xl  bg-white shadow" :class="sideBarWidth"
               v-if="scopes.length">
            <filters :scopes="scopes"></filters>
          </div>
        </div>
      </transition>
      <div class="m-3 relative" :class="ticketsWidth">

        <div class="flex justify-center" v-if="loading && !initLoading">
          <div class="loader ease-linear rounded-full border-4 border-t-4 border-gray-200 h-24 w-24 mt-64"></div>
        </div>


        <div class="transition flex flex-col ease-in-out" v-else>
          <div v-for="ticket in tickets.data">
            <ticket :ticket="ticket"></ticket>
          </div>
          <div class="flex justify-center">
            <pagination :pagination="tickets" @paginate="loadTickets(false)"></pagination>
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
  },
  computed: {
    sideBarWidth() {
      // return this.sidebar_visibility ? 'visible' : 'hidden';
    },
    ticketsWidth() {
      return this.sidebar_visibility ? 'w-9/12' : 'w-full';
    },

  },
  data() {
    return {
      loading: false,
      initLoading: false,
      scopes: {},
      selected_scope: '',
      sidebar_visibility: false,
      search:'',
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
    loadTickets(spin = true) {
      this.loading = spin;

      axios.get(`/api/ticket?page=${this.tickets.current_page}&scope=${this.selected_scope}&search=${this.search}`).then((response) => {
        this.tickets = response.data.tickets;
        this.scopes = Object.keys(response.data.scopes).map((key) => [key, response.data.scopes[key]]);
        this.selected_scope = response.data.scope;
        this.loading = false;
        this.initLoading = false;
      }).catch(e => {
        this.loading = false;
      });
    },

  },
  components: {Pagination, Ticket, Filters}
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
  transform: translateX(10px);
  opacity: 0;
}
</style>