<template>
  <div class="app">
    <apexcharts width="75%" type="pie" :options="chartOptions" :series="series"></apexcharts>
  </div>
</template>

<script>
import VueApexCharts from 'vue-apexcharts'

export default {
  name: 'ServiceTypeChart',
  props: ['labels', 'values'],

  components: {
    apexcharts: VueApexCharts,
  },
  data: function () {
    return {
      chartOptions: {
        colors: ['#F66D44','#663e1b', '#344c33', '#256e5a',
          '#2D87BB', '#768144',],
        chart: {
          type: 'pie',

        },
        plotOptions: {
          pie: {
            donut: {
              size: '80%',
            }
          }
        },
        dataLabels:{
          style: {
            fontSize: '20px',
            fontFamily: 'Helvetica, Arial, sans-serif',
            fontWeight: 'bold',
          },
        },
        labels: this.labels,
        responsive: [{
          breakpoint: 480,
          options: {
            chart: {
              width: 200
            },
            legend: {
              position: 'bottom'
            }
          }
        }],
        title: {
          text: 'Top Services',
          style: {
            fontSize:  '16px',
            fontWeight:  'bold',
            color:  '#263238'
          },
        }
      },

      series: this.values,
    }
  },
}
</script>

<style scoped>

</style>

