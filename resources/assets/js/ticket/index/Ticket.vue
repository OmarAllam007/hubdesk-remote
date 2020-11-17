<template>
  <transition name="slide-fade">
    <a :href="`ticket/${ticket.id}`">
      <div class="ticket-card hover:shadow-lg" :class="{'border-t-2 border-red-300':ticket.is_overdue}">
        <div class="flex flex-col border-b-2 border-gray-200 pt-8 pb-8 pr-3 pl-3 pl-5 pr-5">
          <div class="flex justify-between">
            <p class="pr-3 text-gray-600 font-bold border-gray-400">#{{ ticket.id }}</p>
            <p class="" v-if="ticket.is_overdue">
              <i class="fa fa-flag text-red-800 shadow-2xl"></i>
            </p>
          </div>
          <p class="pt-3">{{ ticket.subject }}</p>
        </div>
        <div class="flex justify-start bg-gray-200 border border-gray-400 rounded-b-xl pl-4 pr-4">
          <div class="flex flex-col">
            <div class="flex justify-between border-r-2 border-gray-400 p-2 .flex-grow-0">
              <p class="text-gray-600 text-lg sm:text-sm md:text-xl lg:text-xl xl:text-2xl ">
                <i class="fa fa-user"></i>
                Requester</p>
              <p class="pl-4 text-lg sm:text-lg md:text-xl lg:text-xl xl:text-2xl pr-5">{{ ticket.requester }}</p>
            </div>

            <div class="flex  justify-between border-r-2 border-gray-400 p-2">
              <p class="text-gray-600 pr-3 text-lg sm:text-lg md:text-xl lg:text-xl xl:text-2xl"><i
                  class="fa fa-id-card"></i> Employee ID</p>
              <p class="pl-4 text-lg sm:text-lg md:text-xl lg:text-xl xl:text-2xl pr-5">{{ ticket.employee_id }}</p>
            </div>

            <div class="flex justify-between border-r-2 border-gray-400  p-2">
              <p class="text-gray-600 text-lg sm:text-lg md:text-xl lg:text-xl xl:text-2xl"><i class="fa fa-list"></i>
                Category</p>
              <p class="text-lg sm:text-lg md:text-xl lg:text-xl xl:text-2xl pr-5">{{ ticket.category }}</p>
            </div>

          </div>
          <div class="flex flex-col">
            <div class="flex  justify-between border-r-2 border-gray-400 p-2">
              <p class="text-gray-600 text-lg sm:text-lg md:text-xl lg:text-xl xl:text-2xl pl-5"><i
                  class="fa fa-user-secret"></i> Coordinator</p>
              <p class="text-lg sm:text-lg md:text-xl lg:text-xl xl:text-2xl pr-5">
                {{ ticket.technician ? ticket.technician : 'Not Assigned' }}</p>
            </div>

            <div class="flex justify-between border-r-2 border-gray-400 p-2">
              <p class="text-gray-600 text-lg sm:text-lg md:text-xl lg:text-xl xl:text-2xl pl-5 "><i
                  class="fa fa-calendar"></i>
                Creation Date</p>
              <p class="text-lg sm:text-lg md:text-xl lg:text-xl xl:text-2xl pr-5">{{ ticket.created_at }}</p>
            </div>

            <div class="flex flex-wrap  justify-between border-r-2 border-gray-400 p-2">
              <p class="text-gray-600 text-lg sm:text-lg md:text-xl lg:text-xl xl:text-2xl pl-5 "><i
                  class="fa fa-list-alt"></i>
                Subcategory</p>
              <p class="flex text-lg sm:text-lg md:text-xl lg:text-xl xl:text-2xl text-right justify-end pr-5">
                {{ ticket.subcategory }}</p>
            </div>

          </div>

          <div class="flex flex-col">

            <div class="flex justify-between p-2">
              <p class="text-gray-600 text-lg sm:text-lg md:text-xl lg:text-xl xl:text-2xl pl-5 "><i
                  class="fa fa-calendar-check-o"></i> Due Date</p>
              <p class="text-lg sm:text-lg md:text-xl lg:text-xl xl:text-2xl">{{ ticket.due_date }}</p>
            </div>

            <div class="flex justify-between  p-2">
              <p class="text-gray-600 text-lg sm:text-lg md:text-xl lg:text-xl xl:text-2xl pl-5 "><i
                  class="fa fa-compass"></i>
                Status</p>
              <p class="text-lg sm:text-lg md:text-xl lg:text-xl xl:text-2xl">{{ ticket.status }}</p>
            </div>
            <div class="flex justify-between p-2">
              <p class="text-gray-600 text-lg sm:text-lg md:text-xl lg:text-xl xl:text-2xl pl-5 "><i
                  class="fa fa-arrow-circle-up"></i>
                Priority</p>
              <p class="text-lg sm:text-lg md:text-xl lg:text-xl xl:text-2xl">{{ ticket.priority }}</p>
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
  props: ['ticket']
}
</script>

<style scoped>
a {
  text-decoration: none;
  color: inherit;

}
</style>