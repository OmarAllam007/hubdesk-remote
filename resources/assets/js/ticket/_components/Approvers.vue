<template>
  <select multiple>
    <slot></slot>
  </select>
</template>

<script>

export default {
  name: "Approvers",
  props: ["options", "value"],
  data() {
    return {
      approvers: [],
      loading: false,
      cc: []
    }
  },
  mounted: function () {
    var vm = this;
    $(this.$el)
        // init select2
        .select2({data: this.options})
        .val(this.value)
        .trigger("change")
        // emit event on change.
        .on("change", function () {

          // vm.$emit("input", this.value);
        });
  },
  watch: {
    value: function (value) {
      // update value
      $(this.$el)
          .val(value)
          .trigger("change");
    },
    options: function (options) {
      // update options
      $(this.$el)
          .empty()
          .select2({data: options});
    }
  },
  destroyed: function () {
    $(this.$el)
        .off()
        .select2("destroy");
  },
  created() {
    // this.loading = true;
    // axios.get('/list/approvers').then((resposne) => {
    //   this.approvers = resposne.data
    //   this.loading = false;
    // })
  },
}
</script>

<style scoped>

</style>