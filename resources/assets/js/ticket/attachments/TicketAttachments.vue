<template>
  <div>
    <div class="flex flex-col m-5 ">
      <a :href="`/ticket/download-attach/${file.id}`" target="_blank"
         class="flex p-5 bg-white   rounded-2xl  shadow-sm  hover:shadow-md hover:bg-gray-200  mb-5   "
         v-for="file in files">
        <div class="flex justify-start p-2 m-2">
          <i class="fa fa-download "></i>
          <p class="pl-5 ">{{ file.display_name }}</p>
        </div>

        <strong class="pr-5  p-2 m-2">Uploaded By: {{ file.uploaded_by }}</strong>
        <p class="bg-gray-600 text-white   p-2 m-2  pl-5 pr-5  rounded-2xl  ">{{ file.created_at }}</p>
      </a>
    </div>
  </div>
</template>

<script>
export default {
  name: "TicketAttachments",
  props: ['files'],
  data() {
    return {}
  }
}
</script>

<style scoped>
a:hover {
  text-decoration: none;
}

</style>