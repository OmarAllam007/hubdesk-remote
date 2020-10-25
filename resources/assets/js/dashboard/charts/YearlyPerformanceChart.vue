<template>
  <div>
    <div class="barChart">

    </div>
  </div>
</template>

<script>
import c3 from 'c3';
import 'c3/c3.css';
export default {
  name: 'YearlyPerformanceChart',
  props: ['total', 'resolved', 'labels'],

  mounted() {
    c3.generate({
      bindto: this.$el,
      data: {
        type: 'bar',
        columns: [
          ["Total", ...this.total],
          ["Resolved & Closed Ontime", ...this.resolved],
        ],


      },
      axis: {
        x: {
          type: 'category',
          categories: this.labels
        },

        legend: {
          position: 'inset',
          inset: {
            anchor: 'bottom-right',
            y: 10,
            x: 0
          }
        }
      }
    })
  },
}
</script>
