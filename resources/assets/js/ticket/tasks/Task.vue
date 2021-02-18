<template>
  <tr class="hover:bg-yellow-100 bg-white">
    <td>
      <a v-bind:href="'/ticket/'+ task.id" v-if="task.can_show">{{ task.id }}</a>
      <p v-else>{{ task.id }}</p>
    </td>
    <td> {{ task.subject }}</td>
    <td> {{ task.category }}</td>
    <td> {{ task.status }}</td>
    <td> {{ task.created_at }}</td>
    <td> {{ task.requester }}</td>
    <td> {{ task.technician }}</td>
    <td>
      <a v-if="task.can_show" class="bg-blue-500  hover:bg-blue-700 hover:text-white text-white font-bold py-2 px-4 rounded-full mr-2  " v-bind:href="'/ticket/'+ task.id">
        <i class="fa fa-eye"></i>
        Show
      </a>
      <a v-if="task.can_edit" class="bg-orange-500  hover:bg-orange-700  text-white  hover:text-white font-bold py-2 px-4 rounded-full mr-2  "
         :href="'/ticket/tasks/edit/'+ task.id">
        <i class="fa fa-edit"></i>
        Edit
      </a>
      <button class="bg-red-600   hover:bg-red-700  text-white font-bold py-2 px-4  hover:text-white rounded-full mr-2   " v-on:click="deleteTask(task.id)" v-if="task.can_delete">
        <i class="fa fa-trash "></i>
        Delete
      </button>
    </td>
  </tr>
</template>

<script>
export default {
  name: "Task",
  props: ['task'],
  data() {
    return {}
  },
  methods: {
    deleteTask(id) {
    }
  }
}
</script>

<style scoped>

</style>