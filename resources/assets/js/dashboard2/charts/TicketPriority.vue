<template>
  <div>
    <div class="flex justify-center">
      <table class="shadow-md ">
        <thead>
        <tr>
          <th
              class="px-5 py-3 border-b-2 border-gray-200 bg-gray-200   text-sm
            font-semibold text-gray-600 uppercase tracking-wider">
            Priority
          </th>
          <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-200  text-center  text-sm
            font-semibold text-gray-600 uppercase tracking-wider" v-for="th in header">
            {{ th }}
          </th>
          <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-200  text-center  text-sm
            font-semibold text-gray-600 uppercase tracking-wider">
            Total
          </th>
        </tr>
        </thead>
        <tbody>
        <tr class="bg-white hover:bg-yellow-100  " v-for="(priority , priorityName) in ticketsPriority.priorities">
          <td class="px-5 py-5 border-b border-gray-200 text-sm ">
            {{ priorityName }}
          </td>
          <td class="px-5 py-5 border-b border-gray-200 text-sm  text-center" v-for="(item,key) in priority.items">
            {{ item }}
          </td>
          <td class="px-5 py-5 border-b border-gray-200 text-sm  text-center">
            {{ priority.Total }}
          </td>
        </tr>
        </tbody>
        <tfoot>
        <tr>
          <td class="px-5 py-3 border-b-2 border-gray-200 bg-gray-200   text-sm
            font-semibold text-gray-600 uppercase tracking-wider">Total
          </td>
          <td class="px-5 py-3 border-b-2 border-gray-200 bg-gray-200   text-sm
            font-semibold text-gray-600 uppercase tracking-wider text-center" v-for="number in ticketsPriority.footer">
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
          return value + 'HR';
        }
      }
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