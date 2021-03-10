<template>
  <div class="flex flex-col w-full  bg-white shadow-md print:shadow-none">

    <div class="flex overflow-x-scroll print:overflow-visible">
      <table class="w-full shadow-md">
        <thead>
        <tr>
          <th
              class="px-5 py-3 border-b-2 border-gray-200 bg-gray-200   text-md
            font-semibold text-gray-600 uppercase tracking-wider">
            Status
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
        <tr class="bg-white hover:bg-yellow-100  " v-for="(status , statusName) in closedTicketsStatusVsCategory.statuses">
          <td class="px-5 py-5 border-b border-gray-200 text-md  ">
            {{ statusName }}
          </td>
          <td class="px-5 py-5 border-b border-gray-200 text-md   text-center" v-for="(item,key) in status.items">
            {{ item }}
          </td>
          <td class="px-5 py-5 border-b border-gray-200 text-md   text-center">
            {{ status.Total }}
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
              v-for="number in closedTicketsStatusVsCategory.footer">
            {{ number }}
          </td>
        </tr>
        </tfoot>
      </table>
    </div>
    <div class="pie-chart flex justify-center" id="closedStatusVsCategory"></div>
  </div>
</template>

<script>
import c3 from "c3";

export default {
  name: "TicketClosedStatusVsCategory",
  props: ['closedTicketsStatusVsCategory'],
  data() {
    return {
      header: []
    }
  },
  mounted() {
    const data = [];
    this.header = Object.values(this.closedTicketsStatusVsCategory.header);

    for (let i in this.closedTicketsStatusVsCategory.chartData) {
      data.push([i != "" ? i : "Not Assigned", this.closedTicketsStatusVsCategory.chartData[i]]);
    }

    c3.generate({
      bindto: '#closedStatusVsCategory',
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