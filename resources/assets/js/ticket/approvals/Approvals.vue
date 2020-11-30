<template>
  <!--  <div v-if="loading" class="text-center">-->
  <!--    <i class="fa fa-spin fa-2x fa-spinner"></i>-->
  <!--  </div>-->

  <div class="">
    <div v-if="is_task">
      <h5>
        <strong>
          Main Ticket Approvals
        </strong>
      </h5>
      <table class="listing-table">
        <thead class="table-design">
        <tr>
          <th>Sent to</th>
          <th>By</th>
          <th>Sent at</th>
          <th>Stage</th>
          <th>Status</th>
          <th>Comment</th>
          <th>Action Date</th>
          <th>Resend</th>
          <th colspan="3" class="text-center">Actions</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="approval_row of approvals">
          <td>{{ approval_row.approver }}</td>
          <td>{{ approval_row.creator }}</td>
          <td>{{ approval_row.created_at }}</td>
          <td>{{ approval_row.stage }}</td>
          <td>
            {{ approval_row.status }}
          </td>
          <td>
            <strong>{{ approval_row.comment }}</strong>
          </td>
          <td>{{ approval_row.action_date }}</td>
          <td></td>
          <td></td>
        </tr>
        </tbody>
      </table>
    </div>

    <div v-if="approvals_data.length">
      <h5>
        <strong v-if="this.is_task">
          Task Approvals
        </strong>
      </h5>
      <table class="listing-table">
        <thead class="table-design">
        <tr>
          <th>Sent to</th>
          <th>By</th>
          <th>Sent at</th>
          <th>Stage</th>
          <th>Status</th>
          <th>Comment</th>
          <th>Action Date</th>
          <th>Resend</th>
          <th colspan="3" class="text-center">Actions</th>
        </tr>
        </thead>
        <tbody>
        <tr is="approval-row" v-for="(approval, index) in approvals_data"
            @remove="removeApproval(index)"
            :approval="approval" :index="index" :key="approval.id" :class="approval.color"></tr>
        </tbody>
      </table>
    </div>
    <!--    @can('submit_approval',$ticket)-->

    <div class="col-md-10" v-if="submit_approval">
      <div class="form-group">
        <button @click.prevent="addNewLevel" class="btn btn-sm btn-primary btn-rounded btn-outlined"><i
            class="fa fa-plus"></i> Add New Approval
        </button>
      </div>
      <div v-for="(level, key) of levels">
        <approval-item :level="level" :users="users" :index="key" :key="key" :stages="stages_count"></approval-item>
      </div>

      <div class="form-group">
        <button type="submit" class="btn btn-success" @click="sendApproval()" :disabled="!can_submit">
          <i class="fa fa-spin fa-spinner" v-if="loading"></i>
          <i class="fa fa-check-circle" v-else></i> Send
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import ApprovalItem from "./ApprovalItem";
import ApprovalRow from "./ApprovalRow";
import {EventBus} from "../../EventBus";
import _ from "lodash";

export default {
  name: "Approvals",
  props: ["ticket_id", "is_task", "approvals", 'task_approvals', 'submit_approval', 'templates'],

  data() {
    return {
      levels: [],
      users: [],
      loading: false,
      approvals_data: [],
      approval_stages: '',
    }
  },
  created() {
    EventBus.$on('remove-approval-item', (index) => {
      this.removeLevel(index);
    });

  },
  mounted() {
    this.init()
  },

  methods: {
    init() {
      this.loading = true;
      this.addNewLevel();

      axios.get('/list/approvers').then((response) => {
        this.users = response.data;
        this.loading = false;
      });

      this.approvals_data = this.is_task ? this.task_approvals : this.approvals;
    },
    removeApproval(approvalIndex) {
      this.approvals_data.splice(approvalIndex, 1);
    },
    addNewLevel() {
      this.levels.push({
        'approver': null,
        'description': '',
        template_id: 0,
        questions: [],
        new_stage: false,
        attachments: [],
        stage: '',
      });
    },
    removeLevel(index) {
      if (this.levels.length > 1) {
        this.levels.splice(index, 1)
      }
    },
    sendApproval() {
      this.loading = true;
      var formData = new FormData;
      formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

      for (var i = 0; i < this.levels.length; ++i) {
        formData.append(`approvals[${i}][description]`, this.levels[i].description);
        formData.append(`approvals[${i}][approver_id]`, this.levels[i].approver.id);
        formData.append(`approvals[${i}][new_stage]`, this.levels[i].new_stage ? 1 : 0);
        formData.append(`approvals[${i}][template_id]`, this.levels[i].template_id);
        formData.append(`approvals[${i}][stage]`, this.levels[i].stage);


        let questions = this.levels[i].questions;
        for (var q = 0; q < questions.length; q++) {
          formData.append(`approvals[${i}][questions][${q}][description]`, questions[q].description);
        }

        let attachments = this.levels[i].attachments;
        console.log(attachments.length)
        for (var l = 0; l < attachments.length; l++) {
          formData.append(`approvals[${i}][attachments][${l}]`, attachments[l]);
        }
      }

      console.log(formData);

      axios.post(`/approval/approval/${this.ticket_id}`, formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        },
      }).then((response) => {
        this.loading = false;
        response.data.forEach((approval) => {
          this.approvals_data.push(approval);
          this.approvals_data = _.orderBy(this.approvals_data, ['stage'], ['asc'])
        })
        this.clearForm();
      }).catch((e) => {
        this.loading = false;
      });


    },
    clearForm() {
      this.levels = [];
      this.addNewLevel();
    }
  },

  computed: {
    can_submit() {
      if (this.loading) {
        return false;
      }

      var validated = true;

      for (var level of this.levels) {
        var filledQuestions = true;
        // if(level.questions.length){
        level.questions.forEach((question) => {
          if (!question.description) {
            filledQuestions = false;
          }
        });
        // }
        let has_description = level.description || level.template_id;

        if (!level.approver || !has_description || !filledQuestions) {
          validated = false;
          break;
        }
      }
      return validated;
    },

    stages_count() {
      var keys = _.map(this.approvals_data, (item) => {
        return item.stage;
      })

      return _.uniq(keys)
    }
  },
  components: {ApprovalItem, ApprovalRow}
}

</script>

<style scoped>

</style>