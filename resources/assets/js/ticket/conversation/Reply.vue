<template>
  <div>
    <a :href="`#${reply.id}`" class="anchor" @click="isCollapsed = !isCollapsed">
      <div class="flex  bg-white shadow-lg rounded-2xl mb-3 ">
        <div class="w-2 bg-viola"></div>
        <div class="flex flex-col">
          <div>
            <div class="flex justify-between px-2 py-3">
              <div class="flex">
                <i class="fa fa-chevron-right pr-1 hover:text-orange-700" v-if="isCollapsed"></i>
                <i class="fa fa-chevron-down pr-1 hover:text-orange-700" v-if="!isCollapsed"></i>
                <p class="text-xl font-semibold text-gray-800">By: {{ reply.name }}</p>
              </div>
              <div class="flex justify-end">
                <p class="bg-gray-200 p-2 m-2 rounded-2xl ">{{ reply.status }}</p>
                <p class="text-gray-800 p-2 m-2">{{ reply.created_at }}</p>
              </div>
            </div>
          </div>
          <div class="p-5" v-if="!isCollapsed">
            <p class="text-gray-600" v-html="reply.content"></p>
          </div>
        </div>

      </div>
    </a>
  </div>
</template>

<script>
export default {
  name: "Reply",
  props: ['reply'],
  data() {
    return {
      isCollapsed: true
    }
  },
}
</script>

<style scoped>
.anchor {
  /*text-decoration: none;*/
  @apply text-gray-800 hover:text-gray-800
}

.anchor:hover {
  /*text-decoration: none;*/
  @apply text-gray-800
}
</style>