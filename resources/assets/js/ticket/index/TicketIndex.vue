<template>
  <div>
    <div class="flex justify-center" v-if="initLoading">
      <div class="loader ease-linear rounded-full border-4 border-t-4 border-gray-200 h-24 w-24 mt-64"></div>
    </div>
    <div class="w-full flex p-3">
      <div class="w-3/12 h-full flex flex-col m-3 rounded-2xl border border-gray-500 p-3 bg-white" v-if="scopes.length">
        <filters :scopes="scopes"></filters>
      </div>

      <div class="w-9/12  m-3">
        <div class="flex justify-center" v-if="loading && !initLoading">
          <div class="loader ease-linear rounded-full border-4 border-t-4 border-gray-200 h-24 w-24 mt-64"></div>
        </div>
        <div class="flex flex-col" v-else>
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
    this.selected_scope = sessionStorage.getItem('scope') ? sessionStorage.getItem('scope') : '' ;
    this.loadTickets();
  },

  mounted() {


    this.$on('changeScope', (scope) => {
      this.selected_scope = scope;
      this.tickets.current_page = 1;
      this.loadTickets();
    });
  },
  data() {
    return {
      loading: false,
      initLoading:false,
      scopes: {},
      selected_scope: '',
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

      axios.get(`/api/ticket?page=${this.tickets.current_page}&scope=${this.selected_scope}`).then((response) => {
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
</style>