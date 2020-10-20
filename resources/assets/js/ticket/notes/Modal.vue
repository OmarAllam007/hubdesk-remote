<template>
  <div v-if="isOpened">
    <transition name="modal">
      <div class="modal-mask">
        <div class="modal-wrapper">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" @click="closeModal()">&times;</button>
                <h4 class="modal-title">{{ create_form ? 'Add Note' : 'Edit Note' }} - #{{ note.ticket_id }}</h4>
              </div>
              <div class="modal-body">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <editor v-model="note.note"
                              :init="{
         height: 300,
         menubar: false,
         plugins: [
            'advlist autolink lists link image imagetools charmap print preview anchor',
        'insertdatetime media table paste directionality textcolor colorpicker'
         ],
         toolbar:
           'undo redo | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent table | link | fontselect fontsizeselect | rtl | forecolor'
       }"

                      ></editor>
                    </div>
                    <div class="checkbox">
                      <label><input type="checkbox" v-model="note.display_to_requester"
                                    name="display_to_requester">{{ 'Show this note to Requester' }}
                      </label>
                    </div>
                    <div class="checkbox">
                      <label><input type="checkbox" v-model="note.email_to_technician" name="email_to_technician"
                                    id="email_to_technician">
                        {{ 'E-mail this note to the technician' }}</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-success submitNote" @click="createOrUpdate">
                  <i class="fa fa-check-circle"></i>
                  {{ create_form ? 'Add Note' : 'Update Note' }}
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal" @click="closeModal()">{{
                    'Close'
                  }}
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
import Editor from '@tinymce/tinymce-vue'
import axios from "axios";

export default {
  name: "Modal",
  props: ['isOpened', 'selected_note', 'create_form'],
  data() {
    return {
      note: {
        ticket_id: this.$parent.ticket_id, note: '',
        display_to_requester: false,
        email_to_technician: false,
      },
      loading: false,
    }
  },
  methods: {
    closeModal() {
      this.$emit('close');
    },
    createOrUpdate() {

      if (this.create_form) {
        this.store();
      } else {
        this.update();
      }

    },
    store() {
      axios.post(`/ticket/note/${this.note.ticket_id}`, this.note).then((response) => {
        this.loading = false
        this.$parent.ticket_notes.push(response.data);
        this.closeModal();
      }).catch((e) => {
        this.loading = false;
        this.closeModal();
      })
    },
    update() {

      axios.post(`/ticket/note/update/${this.note.id}`, this.note).then((response) => {
        this.loading = false;
        this.closeModal();
      }).catch((e) => {
        this.loading = false;
        this.closeModal();
      });

    }

  },
  created() {
    if (this.selected_note.note && !this.create_form) {
      this.note = this.selected_note;
    }
  },
  mounted() {
  },
  components: {Editor}
}
</script>

<style scoped>
.modal-mask {
  position: fixed;
  z-index: 9998;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, .5);
  display: table;
  transition: opacity .3s ease;
}

.modal-wrapper {
  display: table-cell;
  vertical-align: middle;
}
</style>