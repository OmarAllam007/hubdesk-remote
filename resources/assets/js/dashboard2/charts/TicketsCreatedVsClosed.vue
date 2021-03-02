<template>
  <div>
    <table class="shadow-md ">
      <thead>
      <tr>
        <th
            class="px-5 py-3 border-b-2 border-gray-200 bg-gray-200   text-sm
            font-semibold text-gray-600 uppercase tracking-wider">
          Month
        </th>
        <th
            class="px-5 py-3 border-b-2 border-gray-200 bg-gray-200   text-sm
            font-semibold text-gray-600 uppercase tracking-wider">
          Tickets Created
        </th>

        <th
            class="px-5 py-3 border-b-2 border-gray-200 bg-gray-200   text-sm
            font-semibold text-gray-600 uppercase tracking-wider">
          Tickets Closed
        </th>
      </tr>
      </thead>
      <tbody>
      <tr class="bg-white hover:bg-yellow-100  " v-for="(date , dateKey) in rows">
        <td class="px-5 py-5 border-b border-gray-200 text-sm ">
          {{ date.month  }}
        </td>
        <td class="px-5 py-5 border-b border-gray-200 text-sm  text-center">
          {{ date.created }}
        </td>
        <td class="px-5 py-5 border-b border-gray-200 text-sm  text-center">
          {{ date.closed }}
        </td>
      </tr>
      <tr>

      </tr>
      </tbody>
      <tfoot>
      <tr>
        <td class="px-5 py-3 border-b-2 border-gray-200 bg-gray-200   text-sm
            font-semibold text-gray-600 uppercase tracking-wider">Total
        </td>
        <td class="px-5 py-3 border-b-2 border-gray-200 bg-gray-200   text-sm
            font-semibold text-gray-600 uppercase tracking-wider text-center"
            v-for="number in total ">
          {{ number }}
        </td>
      </tr>
      </tfoot>
    </table>
  </div>
</template>

<script>
import moment from 'moment'
export default {
  name: "TicketsCreatedVsClosed",
  props: ['ticketsCreatedClosed'],
  data() {
    return {
      rows: [],
      total: [],
    }
  },
  created() {
    this.rows = this.ticketsCreatedClosed.rows;
    this.total = this.ticketsCreatedClosed.total;
  },
  computed:{
  }
}
</script>

<style scoped>

</style>