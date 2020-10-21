<template>
  <div class="barChart ">
    <apexcharts type="bar"
                :options="chartOptions" :series="chartOptions.series"></apexcharts>
  </div>
</template>

<script>
import VueApexCharts from 'vue-apexcharts'

export default {
  name: 'YearlyPerformanceChart',
  props: ['total', 'resolved', 'labels'],

  components: {
    apexcharts: VueApexCharts,
  },
  data: function () {
    return {
      chartOptions: {
        chart: {
          width: "100%",
          height: 380,
          type: "bar",
          stacked: false,

        },
        dataLabels: {
          enabled: true,
          offsetY: -30,
          style: {
            fontSize: '14px',
            colors: ["#1f0707"]
          }
        },
        colors: ['#9d180d', '#1b3a5b'],
        series: [
          {
            name: 'Total',
            type: 'column',
            data: this.total
          },
          {
            name: "Resolved & Closed Ontime",
            type: 'column',
            data: this.resolved
          },
        ],
        stroke: {
          width: [1, 1]
        },
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: this.labels.length > 3 ? '70%' : '20%',
            dataLabels: {
              position: 'top', // top, center, bottom
            },
          }
        },
        xaxis: {
          categories: this.labels
        },
        yaxis: [
          {
            seriesName: 'Column A',
            axisTicks: {
              show: true
            },
            axisBorder: {
              show: true,
            },
            title: {
              text: "No. of tickets"
            }
          },

        ],
        title: {
          text: 'Performance last 9 months',
          style: {
            fontSize:  '16px',
            fontWeight:  'bold',
            color:  '#263238'
          },
        }
      },
    }
  },
}
</script>
<style scoped>
</style>

