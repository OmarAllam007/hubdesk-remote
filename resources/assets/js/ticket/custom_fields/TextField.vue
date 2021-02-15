<template>
  <div>
    <div class="flex flex-col">
      <label :for="id"
             class="font-semibold">
        {{ label }}
      </label>
      <input
          :id="id"
          class="px-4 py-2
        transition duration-300 border border-gray-300 rounded"
      />
    </div>

  </div>
</template>

<script>
export default {
  name: "TextField",
  props: ['id', 'name', 'label']

}
</script>

<style scoped>

</style>