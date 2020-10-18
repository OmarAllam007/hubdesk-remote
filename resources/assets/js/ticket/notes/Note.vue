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
      <button type="button" id="editNote"
              class="btn btn-primary btn-xs editNote" data-toggle="modal" @click="edit()">
        <i class="fa fa-edit"></i>
      </button>

      <button type="button" id="removeNote"
              class="btn btn-danger btn-xs removeNote" data-toggle="modal"
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