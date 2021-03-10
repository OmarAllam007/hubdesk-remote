<template>
  <div class="c3-chart"></div>
</template>

<script>
// import VueApexCharts from 'vue-apexcharts'
import c3 from 'c3';

export default {
  name: 'ServiceTypeChart',
  props: ['labels', 'values'],

  // components: {
  //   apexcharts: VueApexCharts,
  // },

  mounted() {
    const data = [];
    for (let i in this.labels) {
      data.push([this.labels[i], this.values[i]]);
    }
    c3.generate({
      bindto: this.$el,

      data: {
        type: 'pie',
        columns: data
      }
    });
  }
}
</script>
