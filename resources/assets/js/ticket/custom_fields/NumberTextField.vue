<template>
  <div>
    <div class="flex flex-col">
      <label :for="id"
             class="font-semibold">
        {{ $root.t(label) }} <span v-if="required == '1'" class="text-red-700 font-bold">*</span>
      </label>
      <input
          :id="id"
          class="px-4 py-2
        transition duration-300 border border-gray-400 rounded"

          @input="$emit('input', {id:item_id,value:$event.target.value})"
          autocomplete="off"
          :disabled="this.disableField"
          v-model="fieldValue"
          type="number"
      />
    </div>

  </div>
</template>

<script>
import {EventBus} from "../../EventBus";

export default {
  name: "NumberTextField",
  props: ['id', 'name', 'label', 'value', 'required', 'item_id', 'event_value'],
  data() {
    return {
      disableField: false,
      fieldValue: ''
    }
  },
  created() {
    EventBus.$on('emitForSAPFields', (sapUser) => {
      this.$emit('input', {id: this.item_id, value: sapUser.value[this.event_value]})
      this.fieldValue = sapUser.value[this.event_value]

      if (this.fieldValue !== "") {
        this.disableField = true
      }

      if (this.fieldValue == undefined || this.fieldValue == ""){
        this.disableField = false
      }
    })

  }

}
</script>

<style scoped>

</style>