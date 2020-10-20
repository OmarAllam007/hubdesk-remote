<template>
  <div>
    <div class="form-group" v-if="can_create_note">
      <button type="button" class="btn btn-primary btn-sm btn-rounded btn-outlined" title="Add Note"
              @click="addNew()">
        <i class="fa fa-sticky-note"></i> Add Note
      </button>
    </div>

    <div class="panel panel-default panel-design" v-if="ticket_notes.length">
      <div class="panel-heading">
        <h4 class="panel-title"><i class="fa fa-sticky-note-o"></i> {{ 'Discussion Notes' }}</h4>
      </div>
      <table class="table table-striped table-condensed details-tbl">
        <thead>
        <tr>
          <th>Created By</th>
          <th>Note</th>
          <th>Created at</th>
          <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(note , index) of ticket_notes" is="note" :note="note"
            @editNote="editNote" :key="note.id" :index="index"
            @removeNote="removeNoteRow"></tr>
        </tbody>
      </table>
    </div>
    <div class="container-fluid" v-else>
      <div class="alert alert-warning text-center"><i class="fa fa-exclamation-circle"></i>
        <strong>
          {{ 'No discussion notes found!' }}
        </strong>
      </div>
    </div>
    <modal :isOpened="modalOpened"
           @close="closeModal()"
           v-if="modalOpened"
           :create_form="isCreateForm"
           :selected_note="selected_note"></modal>
  </div>
</template>

<script>
import Note from "./Note";
import Modal from "./Modal";
import axios from "axios";

export default {
  name: "Notes",
  props: ['notes', 'can_create_note', 'ticket_id'],
  data() {
    return {
      modalOpened: false,
      ticket_notes: this.notes,
      selected_note: {'note': ''},
      isCreateForm: true,
    }
  },

  methods: {
    addNew() {
      this.isCreateForm = true;
      this.openModal();
    },
    closeModal(){
      this.modalOpened = false;
      this.isCreateForm = true;
    },
    openModal() {
      this.modalOpened = true;
    },
    editNote(value) {
      this.isCreateForm = false;

      this.selected_note = value;
      this.openModal();
    },
    removeNoteRow(value, id) {
      axios.post(`/ticket/remove-note/${id}`).then((response) => {
        this.ticket_notes.splice(value, 1);
      })
    }

  },
  watch: {
    modalOpened() {
      if (!this.modalOpened) {
        tinymce.remove();
      }
    }
  },
  components: {Note, Modal}
}
</script>

<style scoped>

</style>