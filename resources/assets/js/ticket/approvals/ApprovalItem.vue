<template>
  <div>
    <div class="panel approval-panel ">
      <div class="panel-heading">
        <div style="display: flex; justify-content: space-between">
          <div class="form-group  col-md-6">
            <label>
              Send Approval to:
              <v-select :options="users" label="name" v-model="level.approver" placeholder="Select Approver"
                        class="selection-list"></v-select>
            </label>

          </div>


          <div class="form-group col-md-4">
            <label>
              Template:
            </label>
            <select name="" id="" v-model="level.template_id" class="form-control">
              <option value="0">Select Template</option>
              <option v-for="template of templates" :value="template.id">{{ template.title }}</option>
            </select>

          </div>

          <div class="col-md-2">
            <a href="#" class="text-danger cross-float" type="button" @click.prevent="deleteMe(index)">
              <i class="fa fa-2x  fa-times"></i>
            </a>
          </div>

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

            <tr is="approval-question-row" v-for="(question, qIndex) in level.questions" :index="index" :key="qIndex"
                :qIndex="qIndex"
                :row="qIndex" :question="question"
                @remove="removeQuestion(qIndex)">
            </tr>

            </tbody>
          </table>
        </section>
        <div class="form-group  col-md-6">
          <label>
            Description
          </label>
          <textarea class="form-control" v-model="level.description"
                    name="content" cols="30" rows="8"></textarea>
        </div>
      </div>
      <hr>
      <div class="panel-footer">
        <div style="display: flex; justify-content: space-between">
          <div class="form-group">
            <input type="file" class="form-control input-xs" name="attachments[]" @change="attachFiles($event)"
                   multiple>
          </div>

          <div class="checkbox" v-if="index + 1 > 1 || hasApprovals()">
            <label>
              <input type="checkbox" v-model="level.new_stage"> Add as a new stage
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
      templates: [],
    }
  },
  created() {
    this.templates = this.$parent.templates;
  },
  mounted() {

  },
  methods: {
    addQuestion(index) {
      this.level.questions.push({description: ''});
    },
    removeQuestion(index) {
      this.level.questions.splice(index, 1);
    },
    deleteMe(index) {
      EventBus.$emit('remove-approval-item', index)
    },
    hasApprovals() {
      return this.$parent.approvals_data.length >= 1;
    },
    attachFiles(event) {
      this.level.attachments.push(event.target.files[0])
    }

  },
  watch: {},
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
.cross-float {
  float: right
}

.selection-list {
  width: 500px !important;
}
</style>