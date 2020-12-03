<template>
  <div class="flex flex-col">
    <p class="flex justify-center border-b-2 pb-5 bg-gray-200 p-5  rounded-t-xl"><i class="fa fa-filter"></i> Ticket
      Filters</p>

    <article class="w-full pt-3">
      <div v-for="scope in scopes" class="p-3">
        <button class="w-full flex p-8 rounded-2xl justify-start
                     hover:bg-gray-200 hover:shadow" @click="changeScope(scope[0])"
                :class="isSelectedScope(scope[0])">
                        <span class="text-lg font-bold text-left">
                            <i class="fa fa-chevron-right"></i> {{ scope[1] }}
                        </span>

          <span class="flex justify-end rounded">
            <span class="bg-indigo-800 pl-3 pr-3 rounded-2xl font-bold text-white" v-if="isSelectedScope(scope[0])">
              <span v-if="$parent.loading">
              <i class="fa fa-spinner fa-spin"></i>
            </span>
            <span v-else>{{ getTotal(scope[0]) }}</span>
            </span>
          </span>
        </button>
      </div>
    </article>
  </div>

</template>

<script>

export default {
  name: "Filters",
  props: ['scopes', 'total'],
  data() {
    return {
      filters_visible: true,
      loading: false,
    }
  },
  methods: {
    getTotal(scope) {
      if (scope == this.$parent.selected_scope) {
        return this.$parent.tickets.total;
      }
    },
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