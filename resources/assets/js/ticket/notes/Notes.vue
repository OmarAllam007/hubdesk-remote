<template>
  <div>
    <p class="font-bold text-2xl mb-3 " v-if="can_create_note">{{ $root.t('Discussion Notes') }}</p>
    <div class="shadow-md bg-white p-3 rounded-2xl" v-if="can_create_note">
      <div >
        <button type="button" class="btn btn-primary btn-sm btn-rounded btn-outlined" title="Add Note"
                @click="addNew()">
          <i class="fa fa-sticky-note"></i> {{ $root.t('Add Note') }}
        </button>
        <div class="w-full mt-3" v-if="ticket_notes.length">
          <div class="flex justify-between">
            <table class="table">
              <thead>
              <tr class="bg-viola bg-opacity-75 text-white  font-bold">
                <th>{{ $root.t('Created By') }}</th>
                <th>{{ $root.t('Note') }}</th>
                <th>{{ $root.t('Created at') }}</th>
                <th>{{ $root.t('Actions') }}</th>
              </tr>
              </thead>
              <tbody>
              <tr v-for="(note , index) of ticket_notes" is="note" :note="note"
                  @editNote="editNote" :key="note.id" :index="index"
                  @removeNote="removeNoteRow" class="hover:bg-yellow-100 "></tr>
              </tbody>
            </table>
          </div>
        </div>
        <div v-else class="mt-3">
          <div class="flex bg-yellow-100 shadow-sm  p-5 justify-center text-center rounded-xl"><i
              class="fa fa-exclamation-circle"></i>
            <strong class="pl-2 ">
              {{ $root.t('No discussion notes found') }}
            </strong>
          </div>
        </div>
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
import {EventBus} from "../../EventBus";

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
    closeModal() {
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

        EventBus.$emit('send_notification', 'notes',
            'Ticket Info', `The Note has been deleted successfully`, 'error');
      })
    }

  },

  components: {Note, Modal}
}
</script>

<style scoped>

</style>