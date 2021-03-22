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
                  {{ $root.t('Ticket has been closed by the system') }} {{ $root.t('at') }} {{
                    logItem.created_at
                  }}
                </p>

                <p class="text-black text-left text-gray-700  " v-if="logItem.type == 16">
                  {{ $root.t('Send email to submit the survey by the system') }} {{ $root.t('at') }}
                  {{ log.created_at }}
                </p>

                <p class="text-black text-left text-gray-700  " v-if="isApprovalLog(logItem.type)">
                  {{ $root.t(log.approval_log_description) }} {{ $root.t('at') }}
                  {{ log.created_at }}
                </p>

                <p class="text-black text-left text-gray-700  " v-else>
                  {{
                    $root.t($parent.log_data.is_task ? 'Task ' + logItem.type_action + ' by'
                        : 'Ticket ' + logItem.type_action + ' by ')
                  }} {{ logItem.user }} {{ $root.t('at') }} {{ logItem.created_at }}
                </p>

                <ul v-if="logItem.entries" class="pl-5 ">
                  <li v-for="entry in logItem.entries" class="text-gray-700 ">
                    <i class="fa fa-caret-right"></i>
                    <small>{{ $root.t(entry.label + ' changed from') }}
                      <strong>{{ $root.t(entry.old_Value ? entry.old_Value  : 'None') }}</strong>
                      {{ $root.t('to') }}
                      <strong>{{ $root.t(entry.new_value ? entry.new_value : 'None') }}</strong></small>
                  </li>
                </ul>
                <p class="border-b-2 border-gray-200  m-3" v-if="i < (log.logs.length - 1)"></p>

              </div>
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
      isCollapsed: true
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