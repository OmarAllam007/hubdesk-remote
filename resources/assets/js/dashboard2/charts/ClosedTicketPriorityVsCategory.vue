<template>
  <div class="flex flex-col w-full  bg-white shadow-md print:shadow-none">

    <div class="flex overflow-x-scroll print:overflow-visible">
      <table class="w-full shadow-md">
        <thead>
        <tr>
          <th
              class="px-5 py-3 border-b-2 border-gray-200 bg-gray-200   text-md
            font-semibold text-gray-600 uppercase tracking-wider">
            Priority
          </th>
          <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-200  text-center  text-md
            font-semibold text-gray-600 uppercase tracking-wider" v-for="th in header">
            {{ th }}
          </th>
          <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-200  text-center  text-md
            font-semibold text-gray-600 uppercase tracking-wider">
            Total
          </th>
        </tr>
        </thead>
        <tbody>
        <tr class="bg-white hover:bg-yellow-100  "
            v-for="(priority , priorityName) in closedTicketPriorityCategory.priorities">
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
            font-semibold text-gray-600 uppercase tracking-wider">Total
          </td>
          <td class="px-5 py-3 border-b-2 border-gray-200 bg-gray-200   text-md
                      font-semibold text-gray-600 uppercase tracking-wider text-center"
              v-for="number in closedTicketPriorityCategory.footer">
            {{ number }}
          </td>
        </tr>
        </tfoot>
      </table>
    </div>
    <div class="pt-5 pb-5 "></div>
    <div class="pie-chart flex justify-center" id="closedPriorityCategoryChart"></div>
  </div>
</template>

<script>
import c3 from "c3";

export default {
  name: "ClosedTicketPriorityVsCategory",
  props: ['closedTicketPriorityCategory'],

  data() {
    return {
      header: []
    }
  },
  mounted() {
    const data = [];
    this.header = Object.values(this.closedTicketPriorityCategory.header);

    for (let i in this.closedTicketPriorityCategory.chartData) {
      data.push([i != "" ? i : "Not Assigned", this.closedTicketPriorityCategory.chartData[i]]);
    }

    c3.generate({
      bindto: '#closedPriorityCategoryChart',
      data: {
        type: 'pie',
        columns: data,
        labels: true,
      },
      label: {
        format: function (value, ratio) {
          return value;
        }
      }
    });
  }
}
</script>

<style scoped>

</style>