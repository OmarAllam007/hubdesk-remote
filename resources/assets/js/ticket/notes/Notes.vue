<template>
  <div>
    <div class="form-group" v-if="can_create_note">
      <button type="button" class="btn btn-primary btn-sm btn-rounded btn-outlined" title="Add Note"
              @click="openModal()">
        <i class="fa fa-sticky-note"></i> Add Note
      </button>
    </div>

    <div class="panel panel-default panel-design">
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
        <tr v-for="note of notes" is="note" :note="note">

        </tr>
        </tbody>
      </table>
    </div>
    <modal :isOpened="modalOpened" @close="modalOpened = false"></modal>
  </div>
</template>

<script>
import Note from "./Note";
import Modal from "./Modal";

export default {
  name: "Notes",
  props: ['notes', 'can_create_note'],
  data() {
    return {
      modalOpened: false,
    }
  },
  created() {
  },
  methods: {
    openModal() {
      this.modalOpened = true;
      setTimeout(()=>{},2000)
      tinymce.init({
        selector: '.richeditor',
        plugins: [
          'advlist autolink lists link image imagetools charmap print preview anchor',
          'insertdatetime media table paste directionality textcolor colorpicker'
        ],
        toolbar: 'undo redo | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent table | link | fontselect fontsizeselect | rtl | forecolor', // image
        menubar: false,
        toolbar_items_size: 'small',
        elementpath: false,
        resize: false,
        statusbar: false,
        height: 300,
        paste_data_images: true,
        font_formats: 'Andale Mono=andale mono,times;Arial=arial,helvetica,sans-serif;Arial Black=arial black,avant garde;Book Antiqua=book antiqua,palatino;Comic Sans MS=comic sans ms,sans-serif;Al-said New=courier new,courier;Georgia=georgia,palatino;Helvetica=helvetica;Impact=impact,chicago;Symbol=symbol;Tahoma=tahoma,arial,helvetica,sans-serif;Terminal=terminal,monaco;Times New Roman=times new roman,times;Trebuchet MS=trebuchet ms,geneva;Verdana=verdana,geneva;Webdings=webdings;Wingdings=wingdings,zapf dingbats',
        fontsize_formats: '8pt 10pt 12pt 14pt 18pt 24pt 36pt',
        browser_spellcheck: true
      });
      // tinyMCE.init({selector: ".richeditor"});
    }
  },

  components: {Note, Modal}
}
</script>

<style scoped>

</style>