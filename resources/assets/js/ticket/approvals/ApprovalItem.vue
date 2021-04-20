<template>
  <div>
    <loader class="flex justify-center  p-8 mb-5 rounded-xl border-t-2" v-show="loading"></loader>

    <div v-show="!loading" class="flex flex-col  p-8 mb-5 rounded-xl border-t-2">
      <div class="flex justify-end">
        <a href="#" class="text-danger cross-float" type="button" @click.prevent="deleteMe(index)">
          <i class="fa fa-lg  fa-times"></i>
        </a>
      </div>
      <div class="flex justify-between">
        <div class="w-1/2">
          <div class="form-group">
            <label>
              {{ $root.t('Send Approval to') }}:
              <v-select :options="users" label="name" v-model="level.approver" placeholder="Select Approver"
                        class="selection-list"></v-select>
            </label>
          </div>
        </div>


        <div class="w-1/2" v-if="templates.length">
          <div class="form-group">
            <label>
              {{ $root.t('Template') }}:
            </label>
            <select name="" id="" v-model="level.template_id" class="form-control" @change="updateDescription">
              <option value="0">Select Template</option>
              <option v-for="template of templates" :value="template.id">{{ template.title }}</option>
            </select>
          </div>
        </div>
      </div>

      <div class="flex w-full justify-between pt-5">
        <section class="table-container w-6/12 mr-2">
          <label>
            {{ $root.t('Questions') }}
          </label>
          <table class="listing-table table-bordered">
            <thead class="question-header">
            <tr>
              <th>{{ $root.t('Description') }}</th>
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
        <section class="w-6/12">
          <div class="form-group">
            <label>
              {{ $root.t('Description') }}
            </label>

            <editor trigger="#" v-model="level.description"

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
            <!--            <textarea class="form-control select2 " v-model="level.description"-->
            <!--                      name="content" cols="30" rows="8" v-html="level.description"></textarea>-->
          </div>
        </section>
      </div>

      <div class="flex w-full" v-if="stages.length">
        <div class="w-1/2"></div>
        <div class="w-1/2">
          <div class="form-group">
            <label>
              {{ $root.t('Stage') }}:
            </label>
            <select v-model="level.stage" class="form-control">
              <option value="" selected>{{ $root.t('Select Stage') }}</option>
              <option v-for="stage of stages" :value="stage">{{ stage }}</option>
            </select>
          </div>
        </div>
      </div>

      <div class="flex justify-between">
        <div class="w-1/2">
          <div class="checkbox" v-if="index + 1 > 1 || hasApprovals()">
            <label>
              <input type="checkbox" v-model="level.new_stage"> {{ $root.t('Add as a new stage') }}
            </label>
          </div>
        </div>

        <div class="w-1/2">
          <div class="form-group">
            <input type="file" class="form-control input-xs" name="attachments[]" @change="attachFiles($event)"
                   multiple>
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

import Editor from '@tinymce/tinymce-vue'
import axios from "axios";
import Loader from "../_components/Loader";

Vue.component('v-select', vSelect.VueSelect);
export default {
  name: "ApprovalItem",
  props: ['level', 'users', 'index', 'stages'],
  data() {
    return {
      approver: 0,
      description: '',
      attachments: [],
      questions: [],
      selected_template: '',
      templates:[],
      loading:false
    }
  },
  created() {
    this.loading = true;
    axios.get('/list/templates').then((response) => {
      this.templates = response.data
      this.loading = false;
    })
  },
  mounted() {

  },
  methods: {
    updateDescription() {
      this.templates.forEach((template) => {
        if (this.level.template_id == template.id) {
          this.level.description = template.description;
          return;
        }
        this.level.description = ''
      })
    },
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
      var files = event.target.files;

      for (var i = 0, file; file = files[i]; i++) {
        this.level.attachments.push(file)
      }
    },


  },
  watch: {},
  computed: {
    valid_item() {
      return this.description.length !== '' && this.approver !== 0;
    }
  },
  components: {Loader, ApprovalQuestionRow, vSelect, Editor}

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