<template>
  <div class="flex flex-col bg-white shadow-md print:shadow-none">

    <div class="flex overflow-x-scroll print:overflow-visible ">
      <table class="w-full shadow-md table-striped" id="ticketPriorityTable">
        <thead>
        <tr>
          <th
              class="px-5 py-3 border-b-2 border-gray-200 th-hubtech-color   text-2xl
            font-semibold text-gray-600 uppercase print:text-2xl ">
            Priority
          </th>
          <th class="px-5 py-3 border-b-2 border-gray-200 th-hubtech-color  text-center  text-2xl
            font-semibold text-gray-600 uppercase print:text-2xl " v-for="th in header">
            {{ th }}
          </th>
          <th class="px-5 py-3 border-b-2 border-gray-200 th-hubtech-color  text-center  text-2xl
            font-semibold text-gray-600 uppercase print:text-2xl ">
            Total
          </th>
        </tr>
        </thead>
        <tbody>
        <tr class="bg-white hover:bg-yellow-100  " v-for="(priority , priorityName) in ticketsPriority.priorities">
          <td class="px-5 py-5 border-b border-gray-200 text-2xl   print:text-2xl ">
            {{ priorityName }}
          </td>
          <td class="px-5 py-5 border-b border-gray-200 text-2xl    text-center print:text-2xl "
              v-for="(item,key) in priority.items">
            {{ item }}
          </td>
          <td class="px-5 py-5 border-b border-gray-200 text-2xl    text-center print:text-2xl ">
            {{ priority.Total }}
          </td>
        </tr>
        </tbody>
        <tfoot>
        <tr id="Total">
          <td class="px-5 py-3 border-b-2 border-gray-200 th-hubtech-color    text-2xl  print:text-2xl
            font-semibold text-gray-600 uppercase ">Total
          </td>
          <td class="px-5 py-3 border-b-2 border-gray-200 th-hubtech-color    text-2xl  print:text-2xl
            font-semibold text-gray-600 uppercase  text-center" v-for="number in ticketsPriority.footer">
            {{ number }}
          </td>
        </tr>
        </tfoot>
      </table>
    </div>
    <div class="flex justify-center pt-10">
      <div class="PieChart" id="PieChart"></div>
    </div>
  </div>
</template>

<script>
import c3 from 'c3';
import 'c3/c3.css';
import _ from 'lodash'

export default {
  name: "TicketPriority",
  props: ['ticketsPriority'],
  data() {
    return {
      header: []
    }
  },
  mounted() {

    const data = [];
    this.header = Object.values(this.ticketsPriority.header);

    for (let i in this.ticketsPriority.chartData) {
      data.push([i != "" ? i : "Not Assigned", this.ticketsPriority.chartData[i]]);
    }

    c3.generate({
      bindto: '#PieChart',
      data: {
        type: 'pie',
        columns: data,
        labels: true,
      },
      label: {
        format: function (value, ratio) {
          return value;
        }
      },
    });
  },
  methods: {
    keyExists(key) {
      for (const [key, value] of Object.entries(this.header)) {
        console.log(key, value);
      }
      return _.includes(this.header, key);
    },
  }
}
</script>


<style scoped>
.th-hubtech-color {
  background: rgb(14, 63, 129);
  color: rgb(33, 254, 254);
}
</style>