<template>
  <div class="flex flex-col w-full  bg-white shadow-md print:shadow-none">

    <div class="flex overflow-x-scroll print:overflow-visible">
      <table class="w-full shadow-md table-striped " id="ticketPriorityVsCategory">
        <thead>
        <tr>
          <th
              class="px-5 py-3 border-b-2 border-gray-200 th-hubtech-color   text-2xl  print:text-sm
            font-semibold text-gray-600 uppercase tracking-wider">
            Priority
          </th>
          <th class="px-5 py-3 border-b-2 border-gray-200 th-hubtech-color  text-center  text-2xl
print:text-sm             font-semibold text-gray-600 uppercase tracking-wider" v-for="th in header">
            {{ th }}
          </th>
          <th class="px-5 py-3 border-b-2 border-gray-200 th-hubtech-color  text-center  text-2xl
print:text-sm             font-semibold text-gray-600 uppercase tracking-wider">
            Total
          </th>
        </tr>
        </thead>
        <tbody>
        <tr class="bg-white hover:bg-yellow-100  "
            v-for="(priority , priorityName) in ticketPriorityCategory.priorities">
          <td class="px-5 py-5 border-b border-gray-200 text-2xl  print:text-sm  ">
            {{ priorityName }}
          </td>
          <td class="px-5 py-5 border-b border-gray-200 text-2xl  print:text-sm   text-center" v-for="(item,key) in priority.items">
            {{ item }}
          </td>
          <td class="px-5 py-5 border-b border-gray-200 text-2xl  print:text-sm   text-center">
            {{ priority.Total }}
          </td>
        </tr>
        </tbody>
        <tfoot>
        <tr>
          <td class="px-5 py-3 border-b-2 border-gray-200 th-hubtech-color   text-2xl
print:text-sm             font-semibold text-gray-600 uppercase tracking-wider">Total
          </td>
          <td class="px-5 py-3 border-b-2 border-gray-200 th-hubtech-color   text-2xl
print:text-sm                       font-semibold text-gray-600 uppercase tracking-wider text-center"
              v-for="number in ticketPriorityCategory.footer">
            {{ number }}
          </td>
        </tr>
        </tfoot>
      </table>
    </div>

    <div class="pt-5 pb-5 "></div>
    <div class="pie-chart flex justify-center " id="priorityCategoryChart"></div>
  </div>
</template>

<script>
import c3 from "c3";

export default {
  name: "TicketPriorityVsCategory",
  props: ['ticketPriorityCategory'],

  data() {
    return {
      header: []
    }
  },
  mounted() {
    const data = [];
    this.header = Object.values(this.ticketPriorityCategory.header);

    for (let i in this.ticketPriorityCategory.chartData) {
      data.push([i != "" ? i : "Not Assigned", this.ticketPriorityCategory.chartData[i]]);
    }

    c3.generate({
      bindto: '#priorityCategoryChart',
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
.th-hubtech-color {
  background: rgb(14, 63, 129);
  color: rgb(33, 254, 254);
}
</style>