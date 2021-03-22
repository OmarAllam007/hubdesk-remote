<template>
  <div>
    <div class="flex flex-col ">
      <div v-for="(items,title) in fields">
        <p>{{ title }}</p>
        <div v-for="item in items">
<!--          <text-field :label="item.name" v-if="item.type == 'textfield'"></text-field>-->
          <component :is="item.type" :label="item.name"
                     :name="`cf[${item.id}]`" :id="`cf[${item.id}]`" class="pt-3">
          </component>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Date from "./Date";
import TextField from "./TextField";
import Select from "./Select";


export default {
  name: "Fields",
  props: ['fields'],
  components: {Date, TextField , Select}
}
</script>

<style scoped>

</style>