<template>
  <tr>
    <td class="col-md-2">{{ note.creator }}</td>
    <td class="col-md-6">
      <div v-if="note.can_display">
        <div v-html="note.note"></div>
      </div>
    </td>
    <td class="col-md-2">{{ note.created_at }}</td>
    <td class="col-md-2">
      <button id="editNote" class="btn-xs editNote text-blue-500 bg-transparent border border-solid
      border-blue-500 hover:bg-blue-500 hover:text-white active:bg-blue-500 font-bold uppercase px-5 py-3 rounded
       outline-none focus:outline-none"
              type="button" style="transition: all .15s ease"
              data-toggle="modal"
              @click="edit()">
        <i class="fa fa-edit"></i>
      </button>


      <button id="removeNote"
              class="btn-xs editNote text-red-700 bg-transparent border border-solid border-red-700
              hover:bg-red-700 hover:text-white active:bg-red-700 font-bold uppercase px-5 py-3 rounded
              outline-none focus:outline-none"
              type="button" style="transition: all .15s ease"
              data-toggle="modal"
              @click="remove()">
        <i class="fa fa-trash"></i>
      </button>
    </td>
  </tr>
</template>

<script>

export default {
  name: "Note",
  props: ['note', 'index'],
  data() {
    return {}
  },
  methods: {
    edit() {
      this.$emit('editNote', this.note);
    },
    remove() {
      this.$emit('removeNote', this.index, this.note.id);
    }
  }
}
</script>

<style scoped>

</style>