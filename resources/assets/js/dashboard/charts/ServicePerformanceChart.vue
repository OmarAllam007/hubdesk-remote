<template>
  <div class="lineChart">
    <apexcharts type="bar" :options="chartOptions" :series="chartOptions.series"></apexcharts>
  </div>
</template>

<script>
import VueApexCharts from 'vue-apexcharts'

export default {
  name: 'ServicePerformanceChart',
  props: ['total', 'resolved', 'ontime', 'labels'],

  components: {
    apexcharts: VueApexCharts,
  },
  data: function () {
    return {
      chartOptions: {
        chart: {
          width: '100%',
          height: 160,
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
        colors: ['#9d180d', '#1b3a5b', '#14562a'],
        series: [
          {
            name: 'Total',
            type: 'column',
            data: this.total
          },
          {
            name: "Resolved & Closed",
            type: 'column',
            data: this.resolved
          },
          {
            name: "Ontime",
            type: 'column',
            data: this.ontime
          },
        ],
        stroke: {
          show: true,
          width: 4,
          colors: ['transparent']
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
        fill: {
          opacity: 0.9
        },
        xaxis: {
          labels: {
            rotate: -45
          },
          categories: this.labels,
          tickPlacement: "on"
        },
        yaxis: [
          {
            seriesName: 'No. of tickets',
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
          text: 'Service Performance',
        }
      },
    }
  },
}
</script>
<style scoped>

</style>

