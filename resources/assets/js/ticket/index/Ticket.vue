<template>
  <transition name="slide-fade">
    <a :href="`ticket/${ticket.id}`">
      <div class="ticket-card hover:shadow-lg" :class="{'border-t-2 border-red-300':ticket.is_overdue}">
        <div class="flex flex-col  pt-4 pb-4 pr-3 pl-3 pl-5 pr-5">
          <div class="flex justify-between">
            <p class="ml-2 mr-2" v-if="ticket.is_overdue">
              <i class="fa fa-flag text-red-800  shadow-2xl"></i>
            </p>

            <p class="pr-3 text-gray-600 text-2xl font-bold border-gray-400"><span class="font-extrabold">#{{
                ticket.id
              }} -</span> {{ ticket.subject }}</p>

            <div class="flex justify-end">
              <div class="">
                <div class="pr-2 pl-2 pt-1 pb-1 rounded-full shadow text-lg" :class="getStatusColor"><p class="font-bold text-center overflow-hidden">
                  {{ ticket.status }}</p></div>
              </div>

            </div>
          </div>
          <!--          <p class="pt-3">}</p>-->
        </div>

        <div class="flex justify-start bg-gray-200  rounded-b-xl pl-4 pr-4 shadow-inner">
          <div class="flex flex-col">
            <div class="flex justify-between border-r-2 border-gray-400 p-2 .flex-grow-0">
              <p class="text-gray-600 text-lg sm:text-sm md:text-xl lg:text-xl xl:text-xl ">
                <i class="fa fa-user"></i>
                Requester</p>
              <p class="pl-4 text-lg sm:text-lg md:text-xl lg:text-xl xl:text-xl pr-5 text-right">{{
                  ticket.requester
                }}</p>
            </div>

            <div class="flex  justify-between border-r-2 border-gray-400 p-2">
              <p class="text-gray-600 pr-3 text-lg sm:text-lg md:text-xl lg:text-xl xl:text-xl"><i
                  class="fa fa-id-card"></i> Employee ID</p>
              <p class="pl-4 text-lg sm:text-lg md:text-xl lg:text-xl xl:text-xl pr-5 text-right">{{
                  ticket.employee_id
                }}</p>
            </div>

            <div class="flex justify-between border-r-2 border-gray-400  p-2">
              <p class="text-gray-600 text-lg sm:text-lg md:text-xl lg:text-xl xl:text-xl"><i class="fa fa-list"></i>
                Service</p>
              <p class="text-lg sm:text-lg md:text-xl lg:text-xl xl:text-xl pr-5 text-right">{{ ticket.category }} >
                {{ ticket.subcategory }} </p>
            </div>

          </div>
          <div class="flex flex-col">
            <div class="flex  justify-between p-2">
              <p class="text-gray-600 text-lg sm:text-lg md:text-xl lg:text-xl xl:text-xl pl-5"><i
                  class="fa fa-user-secret"></i> Coordinator</p>
              <p class="text-lg sm:text-lg md:text-xl lg:text-xl xl:text-xl text-right">
                {{ ticket.technician ? ticket.technician : 'Not Assigned' }}</p>
            </div>

            <div class="flex flex-wrap justify-between p-2">
              <p class="text-gray-600 text-lg sm:text-lg md:text-xl lg:text-xl xl:text-xl pl-5 "><i
                  class="fa fa-list-alt"></i>
                Created Date</p>
              <p class="flex text-lg sm:text-lg md:text-xl lg:text-xl xl:text-xl text-right justify-end ">
                {{ ticket.created_at }}</p>
            </div>


            <div class="flex flex-wrap  justify-between  p-2">
              <p class="text-gray-600 text-lg sm:text-lg md:text-xl lg:text-xl xl:text-xl pl-5"><i
                  class="fa fa-compass"></i>
                Due Date</p>
              <p class="flex text-lg sm:text-lg md:text-xl lg:text-xl xl:text-xl text-right justify-end ">
                {{ ticket.due_date }}</p>
            </div>
          </div>


        </div>
      </div>
    </a>

  </transition>
</template>

<script>
export default {
  name: "Ticket",
  props: ['ticket'],
  methods: {},
  computed: {
    getStatusColor() {
      var open = [1, 2, 3];
      var pending = [4, 5, 6];
      var closed = [7, 8, 9];

      if (open.indexOf(parseInt(this.ticket.status_id)) != -1) {
        return 'bg-gray-300';
      } else if (pending.indexOf(parseInt(this.ticket.status_id)) != -1) {
        return 'bg-yellow-700 text-white';
      }else{
        return 'bg-green-700 text-white';
      }
    }
  }
}
</script>

<style scoped>
a {
  text-decoration: none;
  color: inherit;

}
</style>