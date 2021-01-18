<template>
  <div>
    <div class="w-full">
      <div class="m-3 mt-10" style="height: 20% ">
        <div v-for="reply in replies">
          <reply :reply="reply"></reply>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import Reply from "./Reply";

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
  components: {Reply}
}
</script>

<style scoped>
.conversations {
  height: 66.666667%;
}
</style>