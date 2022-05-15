<template>
  <div>
    <div class="flex flex-col ">
      <label :for="id"
             class="font-semibold">
        {{ label }} <span v-if="required == '1'" class="text-red-700 font-bold">*</span>
      </label>
      <input
          type="date"
          :id="id"
          class=" px-4 py-2
        transition duration-300 border border-gray-400 rounded select2"
          :value="value"
          @input="$emit('input', $event.target.value)"
      />
    </div>

  </div>
</template>

<script>
export default {
  name: "Date",
  props: ['id', 'name', 'label', 'value','required']
}
</script>

<style scoped>

</style>