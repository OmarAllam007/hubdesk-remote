<template>
  <div class="lineChartStatus w-auto">
  </div>
</template>

<script>

import c3 from 'c3';
import 'c3/c3.css';

export default {
  name: 'StatusChart',
  props: ['labels', 'values'],

  mounted() {
    c3.generate({
      bindto: this.$el,
      data: {
        type: 'bar',
        columns: [
          ["Total", ...this.values],
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
