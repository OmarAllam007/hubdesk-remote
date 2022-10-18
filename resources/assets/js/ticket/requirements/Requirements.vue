<template>
  <div>
    <div class="m-3 mt-10 ">
      <div class="flex flex-col">
        <div v-for="requirement in requirements" class="flex justify-between bg-white p-5 m-1 rounded-lg shadow-md ">
          <p class="w-2/3">{{ requirement.name }}</p>
          <div class="flex">
            <input type="file">
            <button class="mx-2 p-3 bg-green-600 text-white rounded"> <i class="fa fa-upload"></i> Upload</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "Requirements",
  props: ['ticket_id'],
  data() {
    return {
      requirements: []
    }
  },

  created() {
    this.loadRequirements(this.ticket_id)
  },
  methods: {
    loadRequirements(ticket_id) {
      axios.get(`/kgs/document/requirements/${ticket_id}`)
          .then((response) => {
            this.requirements = response.data
          })
          .catch((error) => {
            console.log(error)
          })
    }
  }

}
</script>

<style scoped>

</style>