<template>
  <div>
    <div class="flex flex-col ">
      <label :for="id"
             class="font-semibold">
        {{ label }}
      </label>
      <input
          type="date"
          :id="id"
          class="px-4 py-2
        transition duration-300 border border-gray-300 rounded
        focus:border-transparent focus:outline-none focus:ring-4
        focus:ring-blue-200"
          :value="value"
          @input="$emit('input', $event.target.value)"
      />
    </div>

  </div>
</template>

<script>
export default {
  name: "Date",
  props: ['id', 'name', 'label', 'value']
}
</script>

<style scoped>

</style>