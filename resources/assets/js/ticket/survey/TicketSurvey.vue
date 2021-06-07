<template>
  <div class="m-3 mt-10 flex flex-col">

  <div class="mt-10"></div>
    <div class="bg-white shadow-md rounded my-6">

    <table class="text-left w-full border-collapse">
      <thead class="bg-gray-300  bg-opacity-75 ">
      <tr>
        <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-lg  text-grey-dark border-b border-grey-light">Question</th>
        <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-lg  text-grey-dark border-b border-grey-light">Answer</th>
        <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-lg  text-grey-dark border-b border-grey-light">Degree</th>
      </tr>
      </thead>
      <tbody>
      <tr class="hover:bg-gray-100" v-for="(answer , key) in survey.answers">
        <td class="py-4 px-6 border-b border-grey-light">{{ answer.question }}</td>
        <td class="py-4 px-6 border-b border-grey-light">{{ answer.answer }}</td>
        <td class="py-4 px-6 border-b border-grey-light">{{ answer.degree }}</td>
      </tr>
      </tbody>
      <tfoot>
      <tr class="bg-gray-300  bg-opacity-75 ">
        <td class="py-4 px-6 bg-grey-lightest font-bold uppercase text-lg  text-grey-dark border-b border-grey-light" colspan="2">Total Degree</td>
        <td class="py-4 px-6 bg-grey-lightest font-bold uppercase text-lg  text-grey-dark border-b border-grey-light">{{survey.total_degree}}</td>
      </tr>
      </tfoot>
    </table>
    </div>
</div>
</template>

<script>
export default {
  name: "TicketSurvey",
  props: ['survey'],


}
</script>

<style scoped>

</style>