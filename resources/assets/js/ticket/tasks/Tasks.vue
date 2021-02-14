<template>
  <div>
    <div class="m-3 mt-10 flex flex-col">
      <loader v-show="loading"></loader>
      <div v-show="!loading">
        <div class="flex justify-end pb-5 ">
          <button
              class="bg-green-500  hover:bg-green-700  text-white font-bold py-2 px-4 rounded-full hover:shadow-md ">
            <i class="fa fa-plus"></i> Create Task
          </button>
          <!--          <button data-toggle="modal" data-target="#TaskForm" type="button"-->
          <!--                  class="btn btn-sm btn-success" title="Create Task">-->
          <!--             Create Task-->
          <!--          </button>-->
        </div>

        <table class="table pt-5 ">
          <thead>
          <tr class=" p-3 bg-viola bg-opacity-75 text-white rounded-tl-xl font-bold">
            <th>ID</th>
            <th>Subject</th>
            <th>Category</th>
            <th>Status</th>
            <th>Created At</th>
            <th>Created By</th>
            <th>Assigned To</th>
            <th>Actions</th>
          </tr>
          </thead>
          <tbody>
          <tr is="task" :task="task" v-for="task in tasks"></tr>
          </tbody>
        </table>
      </div>

    </div>
    <task-modal :showing="true"></task-modal>
  </div>
</template>

<script>
import Loader from "../_components/Loader";
import Task from './Task.vue'
import axios from 'axios';
import TaskModal from "./TaskModal";

export default {
  name: "Tasks",
  props: ['ticket'],
  data() {
    return {
      templates: [],
      tasks: [],
      groups: [],
      categories: [],
      loading: false,
    }
  },
  created() {
    this.getTasks();
  },
  mounted() {

  },
  methods: {
    getTasks() {
      this.loading = true;
      axios.get(`/list/tasks/${this.ticket.id}`)
          .then((response) => {
            this.tasks = response.data.tasks;
            this.templates = response.data.templates;
            this.groups = response.data.groups;
            this.categories = response.data.categories;
            this.loading = false;
          }).catch((e) => {
        this.loading = false;
      })
    }
  },
  components: {Task, Loader, TaskModal}
}
</script>

<style scoped>

</style>