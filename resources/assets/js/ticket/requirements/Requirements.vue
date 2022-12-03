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
  props: ['ticket_id','requirements'],
  data() {
    return {
      attachments:[]
    }
  },

  created() {

  },
  methods: {

  }

}
</script>

<style scoped>

</style>