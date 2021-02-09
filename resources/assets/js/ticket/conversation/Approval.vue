<template>
  <div>
    <a :href="`#${approval.id}`" class="hover:bg-yellow-100 bg-white block " @click="isCollapsed = !isCollapsed">
      <div class="flex  shadow-md  rounded-2xl mb-3 w-full">
        <div class="w-2 " :class="getStatusColor"></div>
        <div class="flex flex-col">
          <div class="shadow-sm ">
            <div class="flex w-full px-2 py-3 ">
              <div class="flex w-1/2"> <!--       name       -->
                <i class="fa fa-chevron-right  p-2 m-2 hover:text-orange-700" v-if="isCollapsed"></i>
                <i class="fa fa-chevron-down  p-2 m-2 hover:text-orange-700" v-if="!isCollapsed"></i>
                <p class="font-semibold p-2 m-2 text-gray-800 w-full text-lg md:text-xl">By: {{ approval.approver }}</p>
              </div>


              <!--              <div class="flex flex-col justify-items-end"> &lt;!&ndash;       date       &ndash;&gt;-->
              <div class="flex justify-end">
                <p class="text-gray-800 p-2 m-2 text-sm  md:text-xl text-right ">{{ approval.created_at }}</p>
              </div>
              <!--              </div>-->
            </div>
          </div>
          <transition name="fade" mode="out-in" v-if="!isCollapsed">
            <div class="flex flex-col ">
              <div class="p-5 bg-gray-200 shadow-inner shadow-2xl">
                <p class="text-black text-center " v-html="approval.content"></p>
                <div class="flex justify-end">
                  <p class="p-2 m-2 rounded-2xl text-center text-base font-bold w-64"
                     :class="getStatusColor">{{ approval.status }}</p>
                </div>
<!--                <div class="flex flex-col" v-if="reply.attachments.length">-->
<!--                  <p><strong>Attachments</strong></p>-->
<!--                  &lt;!&ndash;                  <ul class="list-unstyled">&ndash;&gt;-->

<!--                  <a :href="`download-attach/${attachment.id}`" @click.stop="()=>{}"-->
<!--                     target="_blank" v-for="attachment in reply.attachments"-->
<!--                     class="hover:bg-white  flex z-50 pt-5 pb-5 pl-1 pr-1  rounded-xl ">-->
<!--                    <i class="fa fa-download pl-1 pr-1"></i>-->
<!--                    {{ attachment.display_name }}-->
<!--                  </a>-->
<!--                  &lt;!&ndash;                  <a href="download-attach/" target="_blank"><i class="fa fa-download"></i> {{$attachment->display_name}}</a>&ndash;&gt;-->

<!--                  &lt;!&ndash;                  </ul>&ndash;&gt;-->
<!--                </div>-->
              </div>
            </div>
          </transition>
        </div>
      </div>
    </a>
  </div>
</template>

<script>
export default {
  name: "Approval",
  props: ['approval'],
  data() {
    return {
      isCollapsed: true
    }
  },

  computed:{
    getStatusColor() {
      var open = [1];
      var pending = [4, 5, 6];
      var closed = [7, 8, 9];

      if (parseInt(this.approval.status_id ) == 1) {
        return 'bg-green-700 text-white';
      } else if (parseInt(this.approval.status_id)  == -1) {
        return 'bg-red-700 text-white';
      } else {
        return 'bg-gray-600 text-white';
      }
    }
  }
}
</script>

<style scoped>
a, a:hover, a:active, a:visited, a:focus {
  text-decoration: none;
}
</style>