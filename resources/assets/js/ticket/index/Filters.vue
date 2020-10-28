<template>
  <div class="flex flex-col pt-8 mb-8">
    <p class="flex justify-center border-b-2 pb-3"><i class="fa fa-filter"></i> Ticket Filters</p>

    <article class="w-full pt-3">
      <div v-for="scope in scopes">
        <button class="w-full flex p-8 rounded-2xl justify-start
                     hover:bg-gray-300 hover:shadow" @click="changeScope(scope[0])"
                :class="isSelectedScope(scope[0])">
                        <span class="text-lg font-bold text-left">
                            <i class="fa fa-chevron-right"></i> {{ scope[1] }}
                        </span>
        </button>
      </div>
    </article>
  </div>

</template>

<script>

export default {
  name: "Filters",
  props: ['scopes'],
  data() {
    return {}
  },
  methods: {
    isSelectedScope(scope) {
      return scope == this.$parent.selected_scope ? 'bg-gray-300' : '';
    },
    changeScope(scope) {
      this.$parent.$emit('changeScope', scope)
      sessionStorage.setItem('scope', scope);
    }
  }
}
</script>

<style scoped>

</style>