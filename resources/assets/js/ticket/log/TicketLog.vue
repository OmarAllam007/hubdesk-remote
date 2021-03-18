<template>
  <div>
    <loader v-if="loading"></loader>
    <div class="flex flex-col" v-else>
      <div class="flex bg-green-200 p-5 m-5 rounded-lg shadow-md ">
        <strong v-if="log_data.is_task">
          {{ $translate('Ticket created by') }} {{ log_data.created_by }} {{ $translate('at') }} {{
            log_data.created_at
          }}
        </strong>
        <strong v-else>
          {{ $translate('Task created by') }} {{ log_data.created_by }} {{ $translate('at') }} {{ log_data.created_at }}
        </strong>
      </div>
      <div class="pt-5 "></div>

      <div v-for="log in log_history" class="pl-5 pr-5 ">
        <ticket-log-item :log="log"></ticket-log-item>
      </div>

    </div>
    <!--  </div>-->
  </div>
</template>

<script>
import axios from "axios";
import Loader from "../_components/Loader";
import TicketLogItem from "./TicketLogItem";
import {EventBus} from "../../EventBus";

export default {
  name: "TicketLog",
  components: {TicketLogItem, Loader},
  props: ['ticket_id'],
  data() {
    return {
      log_data: [],
      log_history: [],
      loading: false,
    }
  },
  created() {
    this.getTicketLog();
    EventBus.$on('ticket_updated', () => {
      console.log('updated');
      this.getTicketLog()
    })
  },
  methods: {
    getTicketLog() {
      this.loading = true;
      axios.get(`/list/logs/${this.ticket_id}`).then((response) => {
        this.log_data = response.data;

        this.log_history = _.chain(this.log_data.logs)
            .groupBy('date_created')
            .map((logs, date_created) => ({logs, date_created}))
            .value();

        this.loading = false;
      });
    }
  }
}
</script>

<style scoped>

</style>