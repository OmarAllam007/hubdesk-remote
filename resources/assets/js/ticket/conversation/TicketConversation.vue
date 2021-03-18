<template>
  <div>
    <div>
      <div class="m-3 mt-10 flex flex-col">
        <loader v-show="loading"></loader>
        <p class="text-3xl font-bold  pb-5 " v-if="replies.length" v-show="!loading">Conversation</p>
        <div v-for="reply in replies" v-show="!loading">
          <reply :reply="reply"></reply>
        </div>

        <div class="pt-10" v-if="show_approvals">
          <p class="text-3xl font-bold  pb-5 " v-if="approvals.length" v-show="!loading">Approvals</p>
          <div v-for="approval in approvals" v-show="!loading">
            <approval :approval="approval"></approval>
          </div>
        </div>

        <div class="pt-10 ">
          <reply-form :ticket="ticketData" :approvers="users" :templates="templates" :statuses="statuses"
                      :show_templates="show_templates"
                      v-show="!loading" v-cloak></reply-form>
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
import {EventBus} from "../../EventBus";

export default {
  name: "TicketConversation",
  props: ['ticket'],
  data() {
    var ticketData = this.ticket;

    return {
      replies: {},
      approvals: {},
      users: {},
      statuses: {},
      templates: {},
      show_approvals: false,
      show_templates: false,
      loading: false,
      ticketData,
    }
  },
  created() {
    this.getTicketConversation();
    EventBus.$on('ticket-reply-saved', () => {
      this.getTicketConversation(false);
    });
    EventBus.$on('add_resolution', (reply) => {
      this.replies.unshift(reply.reply);
    });
  },
  methods: {
    getTicketConversation(loading = true) {
      this.loading = loading;
      axios.get(`/list/replies/${this.ticketData.id}`).then((response) => {
        this.ticketData = response.data.ticket;
        this.replies = response.data.replies;
        this.approvals = response.data.approvals;
        this.users = response.data.approvers;
        this.statuses = response.data.statuses;
        this.templates = response.data.templates;
        this.$parent.templates = response.data.templates;
        this.show_approvals = response.data.show_approvals;
        this.show_templates = response.data.show_templates;

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