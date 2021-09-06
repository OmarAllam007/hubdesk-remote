<template>
  <div>
    <div class="flex flex-col ">
      <div v-for="(items,title) in fields" v-if="!is_letter">
        <p v-if="title != 0">{{ title }}</p>
        <div v-for="item in items">
          <!--          <text-field :label="item.name" v-if="item.type == 'textfield'"></text-field>-->
          <component :is="item.type" :label="item.name"
                     :name="`cf[${item.id}]`" :id="`cf[${item.id}]`" class="pt-3">
          </component>
        </div>
      </div>

      <div v-for="(item) in fields" v-if="is_letter">
        <component :is="item.type" :label="item.name"
                   :name="`cf[${item.id}]`" :id="`cf[${item.id}]`" class="pt-3">
        </component>
      </div>

    </div>
  </div>
</template>

<script>
import Date from "./Date";
import TextField from "./TextField";
import Select from "./SelectField";


export default {
  name: "Fields",
  props: ['fields', 'is_letter'],
  components: {Date, TextField, Select}
}
</script>

<style scoped>

</style>