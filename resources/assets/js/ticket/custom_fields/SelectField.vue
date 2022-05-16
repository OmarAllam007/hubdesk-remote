<template>
  <div>
    <div class="flex flex-col ">
      <label :for="id"
             class="font-semibold">
        {{ $root.t(label) }} <span v-if="required == '1'" class="text-red-700 font-bold">*</span>
      </label>

      <selectize
                 class="w-full px-1 "
                 @input="$emit('input', {id:item_id,value:$event})"
                v-if="JSON.parse(options).length > 50"
      >
        <option value="">{{ $root.t('Select') }} {{$root.t(label)}}</option>

        <option v-for="(text, value) in JSON.parse(options)" :value="text">
          {{ text }}
        </option>
      </selectize>

      <selectize
                 class="w-full px-1 "
                 @input="$emit('input', {id:item_id,value:$event})" v-else >
        <option value="">{{ $root.t('Select') }} {{$root.t(label)}}</option>

        <option v-for="(text, value) in JSON.parse(options)" :value="text">
          {{ $root.t(text) }}
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
  components:{Selectize}
}
</script>

<style scoped>

</style>