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
                <div class="flex flex-col w-1/2 pb-5">
                  <label for="template" v-show="templates.length">{{ $root.t('Reply Template') }}:</label>
                  <select v-model="selected_template" class="border border-2 bg-white rounded py-5 outline-none "  id="template"
                          v-show="templates.length"
                          name="template" @change="updateDescription">
                    <option value="" class="py-1">{{ $root.t('Select Template') }}</option>
                    <option :value="template.id" v-for="template in templates" v-html="template.title"
                            class="py-1"></option>
                  </select>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <editor trigger="#" v-model="note.note" id="note-editor" name="note-editor"
                              :init="{
                        paste_data_images: true,
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
                                    name="display_to_requester">{{ $root.t('Show this note to Requester') }}
                      </label>
                    </div>
                    <div class="checkbox">
                      <label><input type="checkbox" v-model="note.email_to_technician" name="email_to_technician"
                                    id="email_to_technician">
                        {{ $root.t('E-mail this note to the technician') }}</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-success submitNote" @click="createOrUpdate">
                  <i class="fa fa-check-circle"></i>
                  {{ create_form ? $root.t('Add Note') : $root.t('Update Note') }}
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal" @click="closeModal()">{{
                    $root.t('Close')
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
import {EventBus} from "../../EventBus";
import _ from "lodash";

export default {
  name: "Modal",
  props: ['isOpened', 'selected_note', 'create_form','templates'],
  data() {
    return {
      note: {
        ticket_id: this.$parent.ticket_id, note: '',
        display_to_requester: false,
        email_to_technician: false,
      },
      loading: false,
      selected_template: '',
    }
  },
  methods: {
    updateDescription() {
      let template = _.find(this.templates, {'id': this.selected_template});
      this.note.note = template ? template.description : '';
    },
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
        EventBus.$emit('ticket_updated');
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