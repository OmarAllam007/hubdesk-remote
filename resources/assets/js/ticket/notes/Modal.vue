<template>
  <div v-if="isOpened">
    <transition name="modal">
      <div class="modal-mask">
        <div class="modal-wrapper">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" @click="closeModal()">&times;</button>
                <h4 class="modal-title">{{ 'Add Notes' }} - #{{ note.ticket_id }}</h4>
              </div>
              <div class="modal-body">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <textarea class="form-control richeditor" id="editor"></textarea>
                    </div>
                    <div class="checkbox">
                      <label><input type="checkbox" name="display_to_requester">{{ 'Show this note to Requester' }}
                      </label>
                    </div>
                    <div class="checkbox">
                      <label><input type="checkbox" name="email_to_technician" id="email_to_technician">
                        {{ 'E-mail this note to the technician' }}</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-success submitNote"><i class="fa fa-check-circle"></i>
                  {{ 'Add Note' }}
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal" @click="removeModal()">{{ 'Close' }}</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </transition>
  </div>
  <!--  <div id="NoteModal" class="modal-comp" role="dialog" v-show="isOpened">-->
  <!--    <div class="modal-dialog modal-lg">-->
  <!--      <div class="modal-content">-->
  <!--        <div class="modal-header">-->

  <!--        </div>-->
  <!--        <div class="modal-body">-->
  <!--            <div class="row">-->
  <!--              <div class="col-md-12">-->
  <!--                <div class="form-group">-->
  <!--                  <textarea class="form-control simpleditor"></textarea>-->
  <!--                </div>-->
  <!--                <div class="checkbox">-->
  <!--                  <label><input type="checkbox" name="display_to_requester"-->
  <!--                                id="display_to_requester">{{ 'Show this note to Requester' }} </label>-->
  <!--                </div>-->
  <!--                <div class="checkbox">-->
  <!--                  <label><input type="checkbox" name="email_to_technician" id="email_to_technician">-->
  <!--                    {{ 'E-mail this note to the technician' }}</label>-->
  <!--                </div>-->

  <!--              </div>-->
  <!--            </div>-->
  <!--&lt;!&ndash;          </div>&ndash;&gt;-->
  <!--          <div class="modal-footer">-->
  <!--            <button type="submit" class="btn btn-success submitNote"><i class="fa fa-check-circle"></i>-->
  <!--              {{ 'Add Note' }}-->
  <!--            </button>-->
  <!--            <button type="button" class="btn btn-default" data-dismiss="modal">{{ 'Close' }}</button>-->
  <!--          </div>-->
  <!--      </div>-->

  <!--    </div>-->
  <!--  </div>-->
</template>

<script>
export default {
  name: "Modal",
  props: ['isOpened'],
  data() {
    return {
      note: {ticket_id: '',},
      type: 1
    }
  },
  methods: {
    closeModal() {
      this.$emit('close');
    },
    removeModal(){

    }
  },
  created() {

  },
  mounted() {
    // $('.modal-mask').bind('shown', function() {
    //   tinyMCE.execCommand('mceAddControl', false, 'editor');
    // });
  }
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