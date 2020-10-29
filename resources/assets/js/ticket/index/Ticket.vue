<template>
  <div class="ticket-card hover:shadow-lg">
    <div class="flex flex-col border-b-2 border-gray-200 pt-8 pb-8 pr-3 pl-3 pl-5 pr-5">
      <p class="pr-3 text-gray-600 font-bold border-gray-400">#{{ ticket.id }}</p>
      <p class="pt-3">{{ticket.subject}}</p>
    </div>
    <div class="flex justify-start bg-gray-200 border border-gray-400 rounded-b-xl pl-4 pr-4" >
      <div class="flex flex-col">
        <div class="flex justify-between border-r-2 border-gray-400 p-2 .flex-grow-0">
          <p class="text-gray-600 text-lg sm:text-sm md:text-xl lg:text-xl xl:text-2xl">
            <i class="fa fa-user"></i>
            Requester</p>
          <p class="pl-4 text-lg sm:text-lg md:text-xl lg:text-xl xl:text-2xl">{{ticket.requester}}</p>
        </div>

        <div class="flex  justify-between border-r-2 border-gray-400 p-2">
          <p class="text-gray-600 pr-3 text-lg sm:text-lg md:text-xl lg:text-xl xl:text-2xl"><i
              class="fa fa-id-card"></i> Employee ID</p>
          <p class="pl-4 text-lg sm:text-lg md:text-xl lg:text-xl xl:text-2xl">{{ticket.employee_id}}</p>
        </div>

        <div class="flex justify-between border-r-2 border-gray-400  p-2">
          <p class="text-gray-600 text-lg sm:text-lg md:text-xl lg:text-xl xl:text-2xl"><i class="fa fa-list"></i>
            Category</p>
          <p class="text-lg sm:text-lg md:text-xl lg:text-xl xl:text-2xl">{{ticket.category}}</p>
        </div>

      </div>
      <div class="flex flex-col">
        <div class="flex  justify-between border-r-2 border-gray-400 p-2">
          <p class="text-gray-600 text-lg sm:text-lg md:text-xl lg:text-xl xl:text-2xl"><i
              class="fa fa-user-secret"></i> Coordinator</p>
          <p class="text-lg sm:text-lg md:text-xl lg:text-xl xl:text-2xl">{{ticket.technician}}</p>
        </div>

        <div class="flex justify-between border-r-2 border-gray-400 p-2">
          <p class="text-gray-600 text-lg sm:text-lg md:text-xl lg:text-xl xl:text-2xl"><i class="fa fa-calendar"></i>
            Creation Date</p>
          <p class="text-lg sm:text-lg md:text-xl lg:text-xl xl:text-2xl">{{ticket.created_at}}</p>
        </div>

        <div class="flex flex-wrap  justify-between border-r-2 border-gray-400 p-2">
          <p class="text-gray-600 text-lg sm:text-lg md:text-xl lg:text-xl xl:text-2xl"><i class="fa fa-list-alt"></i>
            Subcategory</p>
          <p class="flex text-lg sm:text-lg md:text-xl lg:text-xl xl:text-2xl text-right justify-end">{{ticket.subcategory}}</p>
        </div>

      </div>

      <div class="flex flex-col">

        <div class="flex justify-between p-2">
          <p class="text-gray-600 text-lg sm:text-lg md:text-xl lg:text-xl xl:text-2xl"><i
              class="fa fa-calendar-check-o"></i> Due Date</p>
          <p class="text-lg sm:text-lg md:text-xl lg:text-xl xl:text-2xl">{{ticket.due_date}}</p>
        </div>

        <div class="flex justify-between  p-2">
          <p class="text-gray-600 text-lg sm:text-lg md:text-xl lg:text-xl xl:text-2xl"><i class="fa fa-compass"></i>
            Status</p>
          <p class="text-lg sm:text-lg md:text-xl lg:text-xl xl:text-2xl">{{ticket.status}}</p>
        </div>
        <div class="flex justify-between">
          <p class="text-gray-600 text-lg sm:text-lg md:text-xl lg:text-xl xl:text-2xl"></p>
          <p class="text-lg sm:text-lg md:text-xl lg:text-xl xl:text-2xl"></p>
        </div>
      </div>


    </div>

  </div>
</template>

<script>
export default {
  name: "Ticket",
  props:['ticket']
}
</script>

<style scoped>

</style>