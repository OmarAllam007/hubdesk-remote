<template>
  <div v-if="loading">
    <i class="fa fa-spin fa-spinner fa-2x"></i>
  </div>
  <div v-else>
    <tickets-created-vs-closed :ticketsCreatedClosed="ticketsCreatedClosed"></tickets-created-vs-closed>
    <ticket-priority :ticketsPriority="ticketsPriority"></ticket-priority>
  </div>
</template>

<script>
import TicketPriority from "./TicketPriority";
import axios from 'axios';
import TicketsCreatedVsClosed from "./TicketsCreatedVsClosed";

export default {
  name: "Main",
  data() {
    return {
      ticketsPriority: [],
      ticketsCreatedClosed:[],
      loading: false,
    }
  },
  created() {
    this.loading = true;
    axios.get('/dashboard/status-dashboard-data').then((response) => {
      this.ticketsPriority = response.data.ticketsPriority;
      this.ticketsCreatedClosed = response.data.ticketsCreatedClosed;
      _.toArray(this.ticketsCreatedClosed).forEach((item)=>{
        console.log(item)
      })

      this.loading = false;
    }).catch(e => console.log(e));
  },
  components: {TicketsCreatedVsClosed, TicketPriority}
}
</script>

<style scoped>

</style>