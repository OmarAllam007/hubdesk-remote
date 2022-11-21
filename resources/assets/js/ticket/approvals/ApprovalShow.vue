<template>
  <div>
    <notifications-component></notifications-component>

    <div class="p-5 m-5">
      <div class="flex justify-between ">

        <div class="flex-col">
          <h4 class="text-3xl ">
            #{{ approval.ticket.id }} - {{ approval.ticket.subject }} - Approval
          </h4>
          <p class="pt-5 "><span class="font-bold">Employee ID :</span> {{ requester.employee_id }}
            | <span class="font-bold">Name:</span> {{requester.name}} | <span class="font-bold">Job Title:</span> {{requester.job_title}}
          <span v-if="load_from_sap && requester.leave_balance" class="pt-5 "> <span class="font-bold"> | Leave Balance:</span> {{requester.leave_balance}}
            <!--  | <span class="font-bold">Flight Ticket Balance:</span> {{requester.flight_ticket_balance}} -->
          </span>

          </p>


        </div>

        <div class="flex-col">
          <a :href="`/ticket/${approval.ticket.id}`"
             class="bg-transparent hover:bg-blue-500 text-blue-700
                      font-semibold hover:text-white py-2 px-2 border
                      border-blue-500 hover:border-transparent rounded-md  "
             title="Back to ticket" target="_blank"><i class="fa fa-ticket"></i> Display Ticket</a>
          <div></div>
        </div>
      </div>
    </div>
    <div class="p-5 m-5">
      <p class="font-bold text-2xl "> Request Description </p>
      <div class="w-full mt-3 shadow-md bg-white  p-5 rounded-xl ">
        <p v-html="approval.ticket.description"></p>
      </div>
    </div>

    <div class="p-5 m-5">
      <approval-show-ticket-details
          :requester="requester"
          :fields="fields"
          :replies="replies"
          :approvals="approvals"
      >
      </approval-show-ticket-details>
    </div>


    <div class="p-5 m-5">
      <p class="font-bold text-2xl ">Approval Content</p>
      <div class="w-full mt-3 shadow-md bg-white  p-5 rounded-xl ">
        <p v-html="approval.content"></p>
      </div>
    </div>

    <div class="p-5 m-5 flex-col " v-if="questions.length">
      <div>
        <p class="font-bold text-2xl ">Questions</p>
        <div class="w-full mt-5  shadow-md bg-white  p-5 rounded-xl flex-col  " v-for="(question, key) in questions">
          <p>{{ question.description }}</p>
          <div class="flex pt-5 ">
            <div>
              <label :for="`approve`+key" class="radio-online p-5  bg-green-100  hover:bg-green-200 rounded-2xl ">
                <input type="radio" :name="`questions[`+question.id+`]`" :id="`approve`+key"
                       @change="changeApprovalAnswer(question.id,1)">
                Approve
                <i class="fa fa-thumbs-o-up"></i>
              </label>
            </div>
            <div class="pl-5">
              <label :for="`deny`+key" class="radio-online p-5 bg-red-100  hover:bg-red-200  rounded-2xl  ">
                <input type="radio" :name="`questions[`+question.id+`]`" :id="`deny`+key"
                       @change="changeApprovalAnswer(question.id,-1)">
                Deny
                <i class="fa fa-thumbs-o-down"></i>
              </label>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="p-5 m-5 flex-col " v-else>
      <div class="flex pt-5 ">
        <div>
          <label class="radio-online p-5  bg-green-100  hover:bg-green-200 rounded-2xl shadow-md"
                 :class="approvalForm.status == 1 ? 'bg-green-200' :'bg-green-100'"
          >
            <input type="radio" name="questions[]" @change="approvalForm.status = 1">
            Approve
            <i class="fa fa-thumbs-o-up"></i>
          </label>
        </div>
        <div class="pl-5">
          <label class="radio-online p-5  hover:bg-red-200  rounded-2xl shadow-md"
                 :class="approvalForm.status == -1 ? 'bg-red-200' :'bg-red-100'"
                 @change="approvalForm.status = -1">
            <input type="radio" name="questions[]">
            Deny
            <i class="fa fa-thumbs-o-down"></i>
          </label>
        </div>
      </div>
    </div>

    <div class="p-5 m-5">
      <p class="font-bold text-2xl pb-2 ">Comment</p>
      <editor trigger="#" v-model="approvalForm.comment"

              :init="{
          paste_data_images: true,
         height: 200,
         menubar: false,
         plugins: [
            'advlist autolink lists link image imagetools charmap print preview anchor',
            'insertdatetime media table paste directionality textcolor colorpicker'
            ],
         toolbar:
           'undo redo | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent table | link | fontselect fontsizeselect | rtl | forecolor'
       }"

      ></editor>
    </div>

    <div class="p-5 m-5">
      <label for="hidden_comment" class="control-label">
        <input type="checkbox" name="hidden_comment" class="form-check" id="hidden_comment"
               @change="approvalForm.hide_the_comment=!approvalForm.hide_the_comment">
        Mark as a private comment ?
      </label>
    </div>

    <div class="p-5 m-5">
      <button class="bg-transparent
      font-semibold  py-2 px-4  rounded-2xl"
              :class="approval_style"
              @click="submitApproval" :disabled="!can_submit">
        <i class="fa fa-pencil"></i>
        Update
      </button>
    </div>
  </div>
</template>

<script>
import Editor from '@tinymce/tinymce-vue';
import _ from 'lodash';
import ApprovalShowTicketDetails from "./ApprovalShowTicketDetails";
import axios from 'axios';
import {EventBus} from "../../EventBus";
import NotificationsComponent from "../show/NotificationsComponent";

export default {
  name: "ApprovalShow",
  props: ["approval", 'questions', 'requester', 'fields', 'replies', 'approvals','load_from_sap'],
  data() {
    return {
      approvalForm: {
        comment: '',
        questions: [],
        hide_the_comment: false,
        status: ''
      }
    }
  },

  mounted() {
    this.approvalForm.questions = _.map(this.questions, (question, key) => {
      return {'question_id': question.id, 'answer': null};
    });
  },
  computed: {
    can_submit() {
      if (!this.approvalForm.questions.length) {
        return this.approvalForm.status != '';
      }

      var answer = true;

      _.each(this.approvalForm.questions, (aQuestion) => {
        if (aQuestion.answer == null) {
          answer = false;
        }
      })
      return answer;
    },
    approval_style() {
      if (this.can_submit) {
        return 'border border-blue-600 text-blue-600 max-w-max shadow-sm hover:shadow-lg hover:bg-blue-600 hover:text-white';
      }
      return 'border border-gray-500';
    }
  },
  methods: {
    changeApprovalAnswer(question, answer) {
      _.each(this.approvalForm.questions, (aQuestion) => {
        if (aQuestion.question_id == question) {
          aQuestion.answer = answer
        }
      })
    },
    submitApproval() {
      axios.post(`/approval/${this.approval.id}`, this.approvalForm).then((response) => {
        EventBus.$emit('send_notification', 'approvals',
            'Ticket Approval', `Approval has been submitted successfully 👍`, 'success');
        window.location.href = `/ticket/${this.approval.ticket_id}`;
      })

    }
  },


  components: {ApprovalShowTicketDetails, Editor, NotificationsComponent}
}
</script>

<style scoped>

</style>