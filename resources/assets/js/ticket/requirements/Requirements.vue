<template>
  <div>
    <div class="m-3 mt-10 ">
      <div class="flex flex-col">
        <div v-for="requirement in requirements">
          <requirement-row :requirement="requirement"></requirement-row>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import RequirementRow from "./RequirementRow";
export default {
  name: "Requirements",
  components: {RequirementRow},
  props: ['ticket_id'],
  data() {
    return {
      requirements: [],
      attachments:[]
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