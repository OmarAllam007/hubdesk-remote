<template>
  <div class="lineChart w-auto">
    <apexcharts  type="bar" :options="chartOptions" :series="chartOptions.series"></apexcharts>
  </div>
</template>

<script>
import VueApexCharts from 'vue-apexcharts'

export default {
  name: 'StatusChart',
  props: ['labels', 'values'],

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
          stacked: false
        },
        dataLabels: {
          enabled: true,
          offsetY: -30,
          style: {
            fontSize: '14px',
            colors: ["#1f0707"]
          }
        },
        colors: ['#0e272f'],
        series: [
          {
            name: 'No. of tickets',
            type: 'column',
            data: this.values
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
          text: 'Statistics Based on Status',
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

