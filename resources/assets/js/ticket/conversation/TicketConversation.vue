<template>
  <div>
    <div  style="height: 40px; display: block">
      <div class="m-3 mt-10">
        <loader v-show="loading"></loader>
        <div v-for="reply in replies" v-show="!loading">
          <reply :reply="reply"></reply>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import Reply from "./Reply";
import Loader from "../_components/Loader";

export default {
  name: "TicketConversation",
  props: ['ticket_id'],
  data() {
    return {
      replies: {},
      loading: false,
    }
  },
  created() {
    this.getTicketConversation();
  },
  methods: {
    getTicketConversation() {
      this.loading = true;
      axios.get(`/list/replies/${this.ticket_id}`).then((response) => {
        this.replies = response.data.replies
        this.loading = false;
      }).catch((response) => {
        this.loading = false;
      })
    }
  },
  components: {Reply, Loader}
}
</script>

<style scoped>
.conversations {
  height: 66.666667%;
}
</style>