<template>
  <tr>
    <td class="col-md-10">
      <input type="text" class="form-control" :name="`questions[${index}][description]`" placeholder="Description"
             v-model="question.description">
    </td>

    <td class="col-md-2">
      <button class="btn btn-sm btn-warning pull-right" type="button" @click="remove()"><i
          class="fa fa-remove"></i></button>
    </td>
  </tr>
</template>

<script>


import {EventBus} from "../../../EventBus.js";

export default {
  name: "ApprovalQuestionRow",
  props: ['index', 'qIndex', 'question'],
  created() {

  },
  methods: {
    remove() {
      this.$emit(`remove`);
    }
  }
}
</script>

<style scoped>

</style>