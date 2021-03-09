<template>
  <div class="flex flex-col bg-white shadow-md">

    <div class="flex overflow-x-scroll">
      <table class="w-full shadow-md">
        <thead>
        <tr>
          <th
              class="px-5 py-3 border-b-2 border-gray-200 bg-gray-200   text-md
            font-semibold text-gray-600 uppercase ">
            Priority
          </th>
          <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-200  text-center  text-md
            font-semibold text-gray-600 uppercase " v-for="th in header">
            {{ th }}
          </th>
          <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-200  text-center  text-md
            font-semibold text-gray-600 uppercase ">
            Total
          </th>
        </tr>
        </thead>
        <tbody>
        <tr class="bg-white hover:bg-yellow-100  " v-for="(priority , priorityName) in ticketsPriority.priorities">
          <td class="px-5 py-5 border-b border-gray-200 text-md  ">
            {{ priorityName }}
          </td>
          <td class="px-5 py-5 border-b border-gray-200 text-md   text-center" v-for="(item,key) in priority.items">
            {{ item }}
          </td>
          <td class="px-5 py-5 border-b border-gray-200 text-md   text-center">
            {{ priority.Total }}
          </td>
        </tr>
        </tbody>
        <tfoot>
        <tr>
          <td class="px-5 py-3 border-b-2 border-gray-200 bg-gray-200   text-md
            font-semibold text-gray-600 uppercase ">Total
          </td>
          <td class="px-5 py-3 border-b-2 border-gray-200 bg-gray-200   text-md
            font-semibold text-gray-600 uppercase  text-center" v-for="number in ticketsPriority.footer">
            {{ number }}
          </td>
        </tr>
        </tfoot>
      </table>
    </div>
    <div class="pie-chart flex justify-center" id="PieChart"></div>
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
          return value ;
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

</style>