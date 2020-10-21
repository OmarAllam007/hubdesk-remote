<template>
  <div class="lineChart">
    <apexcharts type="bar"
                :options="chartOptions" :series="chartOptions.series"></apexcharts>
  </div>
</template>

<script>
import VueApexCharts from 'vue-apexcharts'

export default {
  name: 'CustomerSatisfactionChart',
  props: ['labels', 'values'],

  components: {
    apexcharts: VueApexCharts,
  },
  data: function () {
    return {
      chartOptions: {
        tooltip: {
          y: {
            formatter: function(value, opts) {
              return value + '%'
            }
          }
        },
        chart: {
          width: "100%",
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
        colors: ['#1b3a5b'],
        series: [
          {
            name: 'Percentage',
            type: 'column',
            data: this.values
          },
        ],
        stroke: {
          width: [1]
        },
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: this.labels.length > 1 ? '70%' : '20%',
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
            seriesName: 'Percentage',
            axisTicks: {
              show: true
            },
            axisBorder: {
              show: true,
            },
            title: {
              text: "Percentage"
            }
          },

        ],
        title: {
          text: 'Customer satisfaction last 9 months',
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
button {
  background: #26E6A4;
  border: 0;
  font-size: 16px;
  color: '#fff';
  padding: 10px;
  margin-left: 28px;
}
</style>

