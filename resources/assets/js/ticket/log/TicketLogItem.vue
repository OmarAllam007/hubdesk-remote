<template>
  <div class="cursor-pointer "
       @click="isCollapsed = !isCollapsed">
    <div class="flex  shadow-sm   rounded-2xl mb-3 w-full
  bg-white ">
      <div class="w-2 rounded-l-md bg-gray-600"></div>
      <div class="flex flex-col">
        <div class="shadow-sm ">
          <div class="flex w-full px-2 py-3 ">
            <div class="flex w-1/2"> <!--       name       -->
              <i class="fa fa-chevron-right  p-2 m-2 hover:text-orange-700" v-if="isCollapsed"></i>
              <i class="fa fa-chevron-down  p-2 m-2 hover:text-orange-700" v-if="!isCollapsed"></i>
              <p class="font-semibold p-2 m-2 w-full text-lg md:text-xl  ">{{ log.date_created }} </p>
            </div>
          </div>
        </div>
        <!--        3,4,5,12-->
        <transition name="fade" mode="out-in">
          <div class="flex flex-col" v-if="!isCollapsed">

            <div class="pl-5  bg-gray-200 shadow-md ">
              <div v-for="(logItem,i) in log.logs" class="pt-3   pb-3  ">
                <p class="text-black text-left text-gray-700  " v-if="logItem.type == 10">
                  {{ $translate('Ticket has been closed by the system') }} {{ $translate('at') }} {{
                    logItem.created_at
                  }}
                </p>

                <p class="text-black text-left text-gray-700  " v-if="logItem.type == 16">
                  {{ $translate('Send email to submit the survey by the system') }} {{ $translate('at') }}
                  {{ log.created_at }}
                </p>

                <p class="text-black text-left text-gray-700  " v-if="isApprovalLog(logItem.type)">
                  {{ $translate(log.approval_log_description) }} {{ $translate('at') }}
                  {{ log.created_at }}
                </p>

                <p class="text-black text-left text-gray-700  " v-else>
                  {{
                    $translate($parent.log_data.is_task ? 'Task ' + logItem.type_action + ' by'
                        : 'Ticket ' + logItem.type_action + ' by ')
                  }} {{ logItem.user }} {{ $translate('at') }} {{ logItem.created_at }}
                </p>

                <ul v-if="logItem.entries" class="pl-5 ">
                  <li v-for="entry in logItem.entries" class="text-gray-700 ">
                    <i class="fa fa-caret-right"></i>
                    <small>{{ $translate(entry.label + ' changed from') }}
                      <strong>{{ $translate(entry.old_Value ? entry.old_Value  : 'None') }}</strong>
                      {{ $translate('to') }}
                      <strong>{{ $translate(entry.new_value ? entry.new_value : 'None') }}</strong></small>
                  </li>
                </ul>

<!--                <p class=" pt-2 pb-5   "></p>-->
                <p class="border-b-2 border-gray-200  m-3" v-if="i < (log.logs.length - 1)"></p>

              </div>


              <!--              <div class="flex justify-end">-->
              <!--                <p class="p-2 m-2 rounded-2xl text-center text-base font-bold w-64"-->
              <!--                   :class="getStatusColor">{{ reply.status }}</p>-->
              <!--              </div>-->
              <!--              <div class="flex flex-col" v-if="reply.attachments.length">-->
              <!--                <p class="text-black"><strong>Attachments</strong></p>-->
              <!--                <a :href="`download-attach/${attachment.id}`" @click.stop="()=>{}"-->
              <!--                   target="_blank" v-for="attachment in reply.attachments"-->
              <!--                   class="hover:bg-white  flex z-50 pt-5 pb-5 pl-1 pr-1  rounded-xl ">-->
              <!--                  <i class="fa fa-download pl-1 pr-1"></i>-->
              <!--                  {{ attachment.display_name }}-->
              <!--                </a>-->
              <!--              </div>-->
            </div>
          </div>
        </transition>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "TicketLogItem",
  props: ['log'],
  data() {
    return {
      isCollapsed: false
    }
  },
  methods: {
    isApprovalLog(type) {
      return [3, 4, 5, 12].includes(type);
    }
  }

}
</script>

<style scoped>

</style>