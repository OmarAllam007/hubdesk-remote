<template>
  <tr>
    <td>{{ note.creator }}</td>
    <td>
      <div v-if="note.can_display">
        <div v-html="note.note">

        </div>
      </div>
    </td>
    <td>{{ note.created_at }}</td>
    <td>
      <button type="button" id="editNote"
              class="btn btn-primary btn-xs editNote" data-toggle="modal" @click="edit(note)">
        <i class="fa fa-edit"></i>
      </button>

      <button type="button" id="removeNote"
              class="btn btn-danger btn-xs removeNote" data-toggle="modal"
              @click="remove(note.id)">
        <i class="fa fa-trash"></i>
      </button>
    </td>
  </tr>
</template>

<script>

export default {
  name: "Note",
  props: ['note'],
  data() {
    return {}
  },
  methods: {
    edit(id) {
      this.$emit('edit-note', id);
    },
    remove(id) {
      this.$emit('remove-note', id);
    }
  }
}
</script>

<style scoped>

</style>