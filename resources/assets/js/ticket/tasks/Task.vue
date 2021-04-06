<template>
  <tr class="hover:bg-yellow-100 bg-white">
    <td>
      <a v-bind:href="'/ticket/'+ task.id" v-if="task.authorization.can_show">{{ task.id }}</a>
      <p v-else>{{ task.id }}</p>
    </td>
    <td> {{ task.subject }}</td>
    <td> {{ task.category }}</td>
    <td> {{ task.status }}</td>
    <td> {{ task.created_at }}</td>
    <td> {{ task.requester }}</td>
    <td> {{ task.technician }}</td>
    <td>
      <a v-if="task.authorization.can_show"
         class="bg-blue-500  hover:bg-blue-700 hover:text-white text-white font-bold py-2 px-4 rounded-full mr-2  "
         v-bind:href="'/ticket/'+ task.id">
        <i class="fa fa-eye"></i>
        {{ $root.t('Show') }}
      </a>
      <a v-if="task.authorization.can_edit"
         class="bg-orange-500  hover:bg-orange-700  text-white  hover:text-white font-bold py-2 px-4 rounded-full mr-2  "
         :href="'/ticket/tasks/edit/'+ task.id">
        <i class="fa fa-edit"></i>
        {{ $root.t('Edit') }}
      </a>
      <button
          class="bg-red-600   hover:bg-red-700  text-white font-bold py-2 px-4  hover:text-white rounded-full mr-2   "
          v-on:click="deleteTask(task.id)" v-if="task.authorization.can_delete">
        <i class="fa fa-trash "></i>
        {{ $root.t('Delete') }}
      </button>
    </td>
  </tr>
</template>

<script>
import axios from 'axios'
import {EventBus} from "../../EventBus";

export default {
  name: "Task",
  props: ['task'],
  data() {
    return {}
  },
  methods: {
    deleteTask(id) {
      axios.delete(`/ticket/tasks/${id}`).then(() => {
        EventBus.$emit('task_deleted');

        EventBus.$emit('send_notification', 'tasks',
            'Ticket Info', `The task has been removed`, 'error');

        EventBus.$emit('ticket_updated');
      });
    }
  }
}
</script>

<style scoped>

</style>