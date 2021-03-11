<template>
  <tr class="hover:bg-yellow-100 bg-white">
    <td>{{ approval_data.approver }}</td>
    <td>{{ approval_data.creator }}</td>
    <td>{{ approval_data.created_at }}</td>
    <td>{{ approval_data.stage }}</td>
    <td>
      {{ approval_data.status }}
    </td>
    <td>
      <i class="fa fa-lg"
         :class="`fa-${approval_data.approval_icon} text-${approval_data.approval_color}`"
         aria-hidden="true"></i>
      <strong v-if="approval_data.hidden_comment === '0'">{{ approval_data.comment }}</strong>
    </td>
    <td>{{ approval_data.action_date }}</td>
    <td>{{ approval_data.resend }}</td>
    <td>
      <a title="Take Action" :href="`/approval/${approval_data.id}`" class="btn btn-xs btn-info"
         v-show="approval_data.can_show">
        <i class="fa fa-gavel"></i>
      </a>
    </td>
    <td>
      <a title="Resend approval" @click="resendApproval(approval_data.id)" :disabled="loading"
         class="btn btn-xs btn-primary" v-if="approval_data.can_resend">
        <i class="fa fa-spin fa-spinner" v-if="loading"></i>
        <i class="fa fa-refresh" v-else></i>
      </a>
    </td>
    <td>
      <button type="submit" title="Remove approval" v-if="approval_data.can_delete"
              @click="removeApproval()"
              class="btn btn-xs btn-warning">
        <i class="fa fa-remove"></i>
      </button>

    </td>
  </tr>
</template>

<script>
import axios from "axios";

export default {
  name: "ApprovalRow",
  props: ['approval', 'index'],
  data() {
    return {
      approval_data: [],
      loading: false,
    }
  },
  mounted() {
    this.approval_data = this.approval;
  },
  methods: {
    removeApproval() {
      this.$emit('remove');

      axios.delete(`/approval/delete/${this.approval.id}`);
    },

    resendApproval(id) {
      this.loading = true;

      axios.get(`/approval/resend/${id}`).then((response) => {
        this.approval_data['resend']++
        this.loading = false;
      });
    },
  }
}
</script>

<style scoped>

</style>