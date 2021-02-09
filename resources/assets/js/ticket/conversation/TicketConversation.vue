<template>
  <div>
    <div>
      <div class="m-3 mt-10 flex flex-col">
        <loader v-show="loading"></loader>
        <p class="text-3xl font-bold  pb-5 " v-if="replies.length">Conversation</p>
        <div v-for="reply in replies" v-show="!loading">
          <reply :reply="reply"></reply>
        </div>

        <div class="pt-10 ">
          <p class="text-3xl font-bold  pb-5 " v-if="approvals.length">Approvals</p>
          <div v-for="approval in approvals" v-show="!loading">
            <approval :approval="approval"></approval>
          </div>
        </div>

        <div class="pt-10 ">
          <reply-form :ticket="ticket" :approvers="users" :templates="templates" :statuses="statuses" v-show="!loading"></reply-form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import Reply from "./Reply";
import Loader from "../_components/Loader";
import Approval from "./Approval";
import ReplyForm from "./ReplyForm";

export default {
  name: "TicketConversation",
  props: ['ticket'],
  data() {
    return {
      replies: {},
      approvals: {},
      users: {},
      statuses: {},
      templates: {},
      loading: false,
    }
  },
  created() {
    this.getTicketConversation();
  },
  methods: {
    getTicketConversation() {
      this.loading = true;
      axios.get(`/list/replies/${this.ticket.id}`).then((response) => {
        this.replies = response.data.replies
        this.approvals = response.data.approvals
        this.users = response.data.approvers
        this.statuses = response.data.statuses
        this.templates = response.data.templates
        this.loading = false;
      }).catch((response) => {
        this.loading = false;
      })
    },

  },
  components: {ReplyForm, Approval, Reply, Loader}
}
</script>

<style scoped>
.conversations {
  height: 66.666667%;
}
</style>