<template>
  <div>
    <div class="m-3 mt-10 flex flex-col">
      <loader v-show="loading"></loader>
      <div v-show="!loading">
        <div class="flex justify-end pb-5 ">
          <button
              class="bg-green-500  hover:bg-green-700  text-white font-bold py-2 px-4 rounded-full hover:shadow-md"
              @click="showModal">
            <i class="fa fa-plus"></i> {{ $root.t('Create Task') }}
          </button>
        </div>
        <div class="flex w-3/2 bg-blue-600 justify-center text-white font-bold p-5 rounded-2xl shadow-md "
             v-if="!tasks.length">
          <p class="text-center "><i class="fa fa-warning"></i> {{ $root.t('No Tasks Found') }}</p>
        </div>
        <table class="table pt-5 " v-if="tasks.length">
          <thead>
          <tr class=" p-3 bg-viola bg-opacity-75 text-white rounded-tl-xl font-bold">
            <th>{{ $root.t('ID') }}</th>
            <th>{{ $root.t('Subject') }}</th>
            <th>{{ $root.t('Category') }}</th>
            <th>{{ $root.t('Status') }}</th>
            <th>{{ $root.t('Created At') }}</th>
            <th>{{ $root.t('Created By') }}</th>
            <th>{{ $root.t('Assigned To') }}</th>
            <th>{{ $root.t('Actions') }}</th>
          </tr>
          </thead>
          <tbody>
          <tr is="task" :task="task" :key="task.id" v-for="task in tasks"></tr>
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
import {EventBus} from "../../EventBus";

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

    EventBus.$on('task_deleted', () => {
      this.getTasks();
    })
  },
  mounted() {

  },
  methods: {
    showModal() {
      EventBus.$emit('show-tasks-modal');
    },
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