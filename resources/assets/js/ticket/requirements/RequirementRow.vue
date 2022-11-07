<template>
  <div class="flex justify-between bg-white p-5 m-1 rounded-lg shadow-md ">
    <p class="w-2/3">{{ requirement.name }}</p>
    <div class="flex">
      <input type="file" :name="`attachments[${requirement.id}]`" @change="attachFiles">

      <button class="mx-2 p-3 bg-green-600 text-white rounded-xl" @click="uploadAttachments">
        <i class="fa  fa-spin fa-spinner" v-if="isLoading"></i> <i class="fa fa-upload" v-else></i> Upload
      </button>

      <a class="mx-2 p-3  rounded" :href="`/kgs/downloadRequirements/${this.$parent.ticket_id}/${requirement.id}`" v-if="requirement.path">
        <i class="fa fa-download"></i>
      </a>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import {EventBus} from "../../EventBus";

export default {
  props: ['requirement'],
  name: "RequirementRow",
  data() {
    return {
      isLoading: false,
      attachments: []
    }
  },
  methods: {
    attachFiles(event) {
      var files = event.target.files;
      for (var i = 0, file; file = files[i]; i++) {
        this.attachments.push(file)
      }
    },
    uploadAttachments() {
      this.isLoading = true
      var form = new FormData;

      let attachments = this.attachments;
      for (var l = 0; l < attachments.length; l++) {
        form.append(`attachments[${l}]`, attachments[l]);
      }
      axios.post(`/kgs/upload-attachment/requirements/${this.$parent.ticket_id}/${this.requirement.id}`,
          form, {
            headers: {
              'Content-Type': 'multipart/form-data'
            },
          }).then((response) => {
        this.isLoading = false
        EventBus.$emit('send_notification', 'replies',
            'Ticket Info', `Uploaded Successfully`, 'success');
      }).catch((r)=>{
        this.isLoading = false
        EventBus.$emit('send_notification', 'replies',
            'Ticket Info', `Error in upload files`, 'error');
      })
    },
  }
}
</script>

<style scoped>

</style>