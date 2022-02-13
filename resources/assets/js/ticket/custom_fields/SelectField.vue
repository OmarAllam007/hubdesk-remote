<template>
  <div>
    <div class="flex flex-col ">
      <label :for="id"
             class="font-semibold">
        {{ label }}
      </label>

      <select class="px-4 py-3 rounded-xl  border select2 " @change="$emit('input', $event.target.value)">
        <option v-for="(text, value) in options" :value="text">
          {{ $parent.t(text) }}
        </option>
      </select>
    </div>
  </div>


</template>

<script>
export default {
  name: "SelectField",
  props: ['id', 'name', 'label', 'value','options'],
  data() {
    return {

    }
  }
}
</script>

<style scoped>

</style>