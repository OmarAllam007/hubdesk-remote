<template>
  <div class="flex flex-col  bg-white shadow-md print:shadow-none">
    <div class="flex">
      <table class="w-full print:overflow-visible table-striped">
        <thead>
        <tr>
          <th
              class="px-5 py-3 border-b-2 border-gray-200    text-2xl
            font-semibold text-gray-600 uppercase th-hubtech-color">
            Month
          </th>
          <th
              class="px-5 py-3 border-b-2 border-gray-200    text-2xl
            font-semibold text-gray-600 uppercase text-center th-hubtech-color">
            Tickets Created
          </th>

          <th
              class="px-5 py-3 border-b-2 border-gray-200 bg-gray-200   text-2xl
            font-semibold text-gray-600 uppercase text-center th-hubtech-color">
            Tickets Closed
          </th>
        </tr>
        </thead>
        <tbody>
        <tr class="bg-white hover:bg-yellow-100  " v-for="(date , dateKey) in rows">
          <td class="px-5 py-5 border-b border-gray-200 text-2xl    ">
            {{ date.month }}
          </td>
          <td class="px-5 py-5 border-b border-gray-200 text-2xl      text-center">
            {{ date.created }}
          </td>
          <td class="px-5 py-5 border-b border-gray-200 text-2xl      text-center">
            {{ date.closed }}
          </td>
        </tr>
        <tr>

        </tr>
        </tbody>
        <tfoot>
        <tr>
          <td class="px-5 py-3 border-b-2 border-gray-200 bg-gray-200   text-2xl
            font-semibold text-gray-600 uppercase th-hubtech-color">Total
          </td>
          <td class="px-5 py-3 border-b-2 border-gray-200 bg-gray-200   text-2xl
            font-semibold text-gray-600 uppercase text-center th-hubtech-color"
              v-for="number in total ">
            {{ number }}
          </td>
        </tr>
        </tfoot>
      </table>
    </div>
    <div class="pt-5 pb-5 "></div>
    <div class="flex justify-center pt-10">
      <div class="BarChart" id="BarChart"></div>
    </div>
  </div>
</template>

<script>
import c3 from "c3";
import _ from 'lodash';

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
  mounted() {
    c3.generate({
      bindto: "#BarChart",
      data: {
        type: 'bar',
        columns: [
          ["Months", ...this.rows.map(row => row.month)],
          ["Ticket Created", ...this.rows.map(row => row.created)],
          ["Ticket Closed", ...this.rows.map(row => row.closed)],
        ],
      },
      axis: {
        x: {
          type: 'category',
          categories: [...this.rows.map(row => row.month)]
        },

        legend: {
          position: 'inset',
          inset: {
            anchor: 'top-right',
            y: 10,
            x: 0
          }
        }
      }
    })
  },
  computed: {}
}
</script>

<style scoped>
.th-hubtech-color {
  background: rgb(14, 63, 129);
  color: rgb(33, 254, 254);
}
</style>