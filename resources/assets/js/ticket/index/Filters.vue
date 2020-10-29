<template>
  <div class="flex flex-col">
<!--    <div class="flex">-->
<!--      <button-->
<!--          class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2-->
<!--          px-4 border border-blue-500 hover:border-transparent rounded"-->
<!--          @click="toggleFilters">-->
<!--        <i class="fa fa-bars"></i>-->
<!--      </button>-->
<!--    </div>-->
    <p class="flex justify-center border-b-2 pb-5 bg-gray-200 p-5  rounded-t-xl"><i class="fa fa-filter"></i> Ticket Filters</p>

    <article class="w-full pt-3">
      <div v-for="scope in scopes" class="p-3">
        <button class="w-full flex p-8 rounded-2xl justify-start
                     hover:bg-gray-200 hover:shadow" @click="changeScope(scope[0])"
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
    return {
      filters_visible: true,
    }
  },
  methods: {
    isSelectedScope(scope) {
      return scope == this.$parent.selected_scope ? 'bg-gray-300' : '';
    },
    changeScope(scope) {
      this.$parent.$emit('changeScope', scope)
      sessionStorage.setItem('scope', scope);
    },
    toggleFilters() {
      this.filters_visible = !this.filters_visible;
      this.$parent.$emit('toggle-sidebar', this.filters_visible);
    }
  }
}
</script>

<style scoped>

</style>