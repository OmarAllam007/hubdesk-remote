<template>
  <div>
    <a class="hover:bg-yellow-100 bg-white block rounded-md  cursor-pointer" @click="isCollapsed = !isCollapsed">
      <div class="flex  shadow-md  rounded-2xl mb-3 w-full">
        <div class="w-2 rounded-l-md " :class="getStatusColor"></div>
        <div class="flex flex-col">
          <div class="shadow-sm ">
            <div class="flex w-full px-2 py-3 ">
              <div class="flex w-1/2"> <!--       name       -->
                <i class="fa fa-chevron-right  p-2 m-2 hover:text-orange-700" v-if="isCollapsed"></i>
                <i class="fa fa-chevron-down  p-2 m-2 hover:text-orange-700" v-if="!isCollapsed"></i>
                <p class="font-semibold p-2 m-2 text-gray-800 w-full text-lg md:text-xl">{{ $root.t('By') }}:
                  {{ reply.name }}</p>
              </div>


              <!--              <div class="flex flex-col justify-items-end"> &lt;!&ndash;       date       &ndash;&gt;-->
              <div class="flex justify-end">
                <i class="fa fa-paperclip p-2 m-2 " v-if="reply.attachments.length"></i>
                <p class="text-gray-800 p-2 m-2 text-sm  md:text-xl text-right ">{{ reply.created_at }}</p>

              </div>
              <!--              </div>-->
            </div>
          </div>
          <transition name="fade" mode="out-in">
            <div class="flex flex-col" v-if="!isCollapsed">

              <div class="p-5 bg-gray-200 shadow-inner shadow-2xl">
                <div class="flex w-full" v-if="reply.cc.length">
                  <p class="p-1 rounded-2xl text-center text-md font-bold ">{{ $root.t('Cc') }}: {{ reply.cc }}</p>
                </div>
                <p class="text-black text-center " v-html="reply.content"></p>
                <div class="flex justify-end">
                  <p class="p-2 m-2 rounded-2xl text-center text-base font-bold w-64"
                     :class="getStatusColor">{{ $root.t(reply.status) }}</p>
                </div>
                <div class="flex flex-col" v-if="reply.attachments.length">
                  <p class="text-black"><strong>Attachments</strong></p>
                  <a :href="`download-attach/${attachment.id}`" @click.stop="()=>{}"
                     target="_blank" v-for="attachment in reply.attachments"
                     class="hover:bg-white  flex z-50 pt-5 pb-5 pl-1 pr-1  rounded-xl ">
                    <i class="fa fa-download pl-1 pr-1"></i>
                    {{ attachment.display_name }}
                  </a>
                </div>
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
  name: "Reply",
  props: ['reply'],
  data() {
    return {
      isCollapsed: true
    }
  },
  computed: {
    getStatusColor() {
      var open = [1, 2, 3];
      var pending = [4, 5, 6];
      var closed = [7, 8, 9];

      if (open.indexOf(parseInt(this.reply.status_id)) != -1) {
        return 'bg-gray-600 text-white';
      } else if (pending.indexOf(parseInt(this.reply.status_id)) != -1) {
        return 'bg-yellow-700 text-white';
      } else {
        return 'bg-green-700 text-white';
      }
    }
  }
}
</script>

<style scoped>
a, a:hover, a:active, a:visited, a:focus {
  text-decoration: none;

}

a:hover {
  text-decoration: none;
}

.fade-enter-active, .fade-leave-active {
  transition: opacity .1s;
}

.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */
{
  opacity: 0;
}
</style>