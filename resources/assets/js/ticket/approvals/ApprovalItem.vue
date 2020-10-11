<template>
  <div>
    <div class="panel approval-panel ">
      <div class="panel-heading">
        <div style="display: flex; justify-content: space-between">
          <div class="form-group select2-container">
            <label class="col-md-8">
              Send Approval to
              <v-select :options="users" label="name" v-model="approver" placeholder="Select Approver"></v-select>


              <!--              <select class="form-control select2"-->
              <!--                      v-model="approver_id">-->
              <!--                <option value="0">Select Approver</option>-->

              <!--                <option v-for="user of users" :value="approver_id">-->
              <!--                  {{ user.name }} ( {{ user.email }} )-->
              <!--                </option>-->

              <!--              </select>-->
            </label>

          </div>

          <a href="#" class="text-danger" type="button" @click.prevent="deleteMe(index)">
            <i class="fa fa-2x  fa-times"></i>
          </a>

        </div>
      </div>
      <hr>
      <div class="panel-body">
        <section class="table-container col-md-6">
          <table class="listing-table table-bordered">
            <thead class="question-header">
            <tr>
              <th class="col-md-10">Questions</th>
              <th>
                <a class="btn btn-sm btn-primary btn-xs" @click="addQuestion(index)"
                   type="button"><i
                    class="fa fa-plus-circle"></i></a>
              </th>
            </tr>
            </thead>
            <tbody>

            <tr is="approval-question-row" v-for="(question, qIndex) in questions" :index="index" :key="qIndex"
                :qIndex="qIndex"
                :row="qIndex" :question="question"
                @remove="removeQuestion(qIndex)" class="col-md-12">
            </tr>

            </tbody>
          </table>
        </section>
        <div class="form-group  col-md-6">
          <label>
            Description
          </label>
          <textarea class="form-control" v-model="description"
                    name="content" cols="30" rows="8"></textarea>
        </div>
      </div>
      <hr>
      <div class="panel-footer">
        <div style="display: flex; justify-content: space-between">
          <div class="form-group">
            <input type="file" class="form-control input-xs" name="attachments[]" multiple>
          </div>

          <div class="checkbox" v-if="index + 1 > 1">
            <label>
              <input type="checkbox"> Add as a new stage
            </label>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Vue from 'vue'
import ApprovalQuestionRow from "./questions/ApprovalQuestionRow";
import {EventBus} from "../../EventBus";
import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css';

Vue.component('v-select', vSelect.VueSelect);
export default {
  name: "ApprovalItem",
  props: ['level', 'users', 'index'],
  data() {
    return {
      approver: 0,
      description: '',
      attachments: [],
      questions: [],
    }
  },
  created() {
    // EventBus.$on(`remove-approval-question-${this.index}`, (question) => {
    //   this.removeQuestion(question);
    // });
  },
  mounted() {
    $('.select2').on('change', function (e) {
      var data = e;
      console.log(e);
      // console.log(data);
    });
  },
  methods: {
    addQuestion(index) {
      this.questions.push({description: ''});
    },
    removeQuestion(index) {
      this.questions.splice(index, 1);
    },
    deleteMe(index) {
      EventBus.$emit('remove-approval-item', index)
    },

  },
  computed: {
    valid_item() {
      return this.description.length !== '' && this.approver !== 0;
    }
  },
  components: {ApprovalQuestionRow, vSelect}

}
</script>

<style scoped>
/*.select2 {*/
/*  width: 50%;*/
/*}*/
</style>