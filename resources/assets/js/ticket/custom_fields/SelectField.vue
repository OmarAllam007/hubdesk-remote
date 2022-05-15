<template>
  <div>
    <div class="flex flex-col ">
      <label :for="id"
             class="font-semibold">
        {{ label }} <span v-if="required == '1'" class="text-red-700 font-bold">*</span>
      </label>

      <selectize
                 class="w-full px-1 "
                 @input="$emit('change-list', {id:item_id,value:$event})" >
        <option value="">Select {{label}}</option>
        <option v-for="(text, value) in JSON.parse(options)" :value="text">
          {{ $parent.t(text) }}
        </option>
      </selectize>
    </div>
  </div>


</template>

<script>
import Selectize from 'vue2-selectize'
import "selectize/dist/css/selectize.bootstrap3.css";

export default {
  name: "SelectField",
  props: ['id', 'name', 'label', 'value','options','required','item_id'],
  data() {
    return {

    }
  },
  mounted() {

    // $(".select2").selectize(this.options);

    // setTimeout(() => {
    //   $('.select2').select2({
    //     width: 'element',
    //     minimumResultsForSearch: Infinity
    //   })
    // }, 2000);
  },
  components:{Selectize}
}
</script>

<style scoped>

</style>