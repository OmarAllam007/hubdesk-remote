<template>
  <div>
    <div class="flex flex-col ">
      <label :for="id"
             class="font-semibold">
        {{ $root.t(label) }} <span v-if="required == '1'" class="text-red-700 font-bold">*</span>
      </label>
      <input
          type="datetime-local"
          :id="id"
          class=" px-4 py-2
        transition duration-300 border border-gray-400 rounded select2"
          :value="value"
          @input="$emit('input', {id:item_id,value:$event.target.value})"
      />
    </div>

  </div>
</template>

<script>
export default {
  name: "TimeDate",
  props: ['id', 'name', 'label', 'value','required','item_id','type']
}
</script>

<style scoped>

</style>