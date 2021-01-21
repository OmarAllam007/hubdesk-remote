<template>
  <div>
    <div class="w-full">
      <div class="rounded-xl shadow-md bg-white m-3 pt-3 pl-4 pr-4 pb-4 border-t-4 border-viola">
        <p v-html="ticket.description"></p>
      </div>
    </div>
    <div class="w-full">
      <div class="m-3 mt-10">
        <p class="font-bold text-2xl ">Request Details</p>
        <div class="w-full mt-3 shadow-md">
          <div class="flex justify-between">
            <div class="w-1/4 p-3 bg-viola bg-opacity-75 text-white rounded-tl-xl font-bold">Category</div>
            <div class="w-1/4 p-3 bg-white hover:bg-yellow-100">{{ ticket.category }}</div>
            <div class="w-1/4 p-3 bg-viola bg-opacity-75 text-white font-bold">Group</div>
            <div class="w-1/4 p-3 bg-white rounded-tr-xl hover:bg-yellow-100">{{ ticket.group }}</div>
          </div>

          <div class="flex justify-between">
            <div class="w-1/4 p-3 bg-viola bg-opacity-75 text-white  font-bold">Subcategory</div>
            <div class="w-1/4 p-3 bg-white hover:bg-yellow-100">{{ ticket.subcategory }}</div>
            <div class="w-1/4 p-3 bg-viola bg-opacity-75 text-white font-bold">Technician</div>
            <div class="w-1/4 p-3 bg-white hover:bg-yellow-100">{{ ticket.technician }}</div>
          </div>

          <div class="flex justify-between">
            <div class="w-1/4 p-3 bg-viola bg-opacity-75 text-white  font-bold">Item</div>
            <div class="w-1/4 p-3 bg-white hover:bg-yellow-100">{{ ticket.item }}</div>
            <div class="w-1/4 p-3 bg-viola bg-opacity-75 text-white font-bold">Priority</div>
            <div class="w-1/4 p-3 bg-white hover:bg-yellow-100">{{ ticket.priority }}</div>
          </div>

          <div class="flex justify-between">
            <div class="w-1/4 p-3 bg-viola bg-opacity-75 text-white  font-bold">SubItem</div>
            <div class="w-1/4 p-3 bg-white hover:bg-yellow-100">{{ ticket.subItem }}</div>
            <div class="w-1/4 p-3 bg-viola bg-opacity-75 text-white font-bold">Service Cost</div>
            <div class="w-1/4 p-3 bg-white hover:bg-yellow-100">{{ ticket.service_cost }}</div>
          </div>

          <div class="flex justify-between">
            <div class="w-1/4 p-3 bg-viola bg-opacity-75 text-white  font-bold">SLA</div>
            <div class="w-1/4 p-3 bg-white hover:bg-yellow-100">{{ ticket.sla }}</div>
            <div class="w-1/4 p-3 bg-viola bg-opacity-75 text-white font-bold">Due Time</div>
            <div class="w-1/4 p-3 bg-white hover:bg-yellow-100">{{ ticket.due_date }}</div>
          </div>
        </div>

        <div class="mt-10"></div>

        <p class="font-bold text-2xl ">Additional Information</p>
        <div class="w-full mt-3 ">
          <div class="flex flex-col shadow-md">
            <div v-for="(field , key) in ticket.fields" >
              <div class="flex">
                <div class="w-1/2 p-3 bg-viola bg-opacity-75 text-white  font-bold">{{ field.name }}</div>
                <div class="w-1/2 p-3 bg-white hover:bg-yellow-100">{{ field.value }}</div>
              </div>
            </div>
          </div>
        </div>

        <div class="mt-10"></div>

        <p class="font-bold text-2xl ">Requester Details</p>
        <div class="w-full mt-3 shadow-md">
          <div class="flex justify-between">
            <div class="w-1/4 p-3 bg-viola bg-opacity-75 text-white rounded-tl-xl font-bold">Name</div>
            <div class="w-1/4 p-3 bg-white hover:bg-yellow-100">{{ ticket.requester }}</div>
            <div class="w-1/4 p-3 bg-viola bg-opacity-75 text-white font-bold">Business Unit</div>
            <div class="w-1/4 p-3 bg-white rounded-tr-xl hover:bg-yellow-100">{{ $parent.data.requester.company }}</div>
          </div>

          <div class="flex justify-between">
            <div class="w-1/4 p-3 bg-viola bg-opacity-75 text-white  font-bold">Department</div>
            <div class="w-1/4 p-3 bg-white hover:bg-yellow-100">{{ $parent.data.requester.department }}</div>
            <div class="w-1/4 p-3 bg-viola bg-opacity-75 text-white font-bold">Job title</div>
            <div class="w-1/4 p-3 bg-white hover:bg-yellow-100">{{ $parent.data.requester.job_title }}</div>
          </div>

          <div class="flex justify-between">
            <div class="w-1/4 p-3 bg-viola bg-opacity-75 text-white  font-bold">Email</div>
            <div class="w-1/4 p-3 bg-white hover:bg-yellow-100">{{ $parent.data.requester.email }}</div>
            <div class="w-1/4 p-3 bg-viola bg-opacity-75 text-white font-bold">Employee ID</div>
            <div class="w-1/4 p-3 bg-white hover:bg-yellow-100">{{ $parent.data.requester.employee_id }}</div>
          </div>

          <div class="flex justify-between">
            <div class="w-1/4 p-3 bg-viola bg-opacity-75 text-white  font-bold">Direct Manager Name</div>
            <div class="w-1/4 p-3 bg-white hover:bg-yellow-100">{{ $parent.data.requester.manager_name }}</div>
            <div class="w-1/4 p-3 bg-viola bg-opacity-75 text-white font-bold">Direct Manager Email</div>
            <div class="w-1/4 p-3 bg-white hover:bg-yellow-100">{{ $parent.data.requester.manager_email }}</div>
          </div>

          <div class="flex justify-between">
            <div class="w-1/4 p-3 bg-viola bg-opacity-75 text-white  font-bold">Mobile</div>
            <div class="w-1/4 p-3 bg-white hover:bg-yellow-100">{{ $parent.data.requester.mobile1 }}</div>
            <div class="w-1/4 p-3 bg-viola bg-opacity-75 text-white font-bold">Phone</div>
            <div class="w-1/4 p-3 bg-white hover:bg-yellow-100">{{ $parent.data.requester.phone }}</div>
          </div>
        </div>

        <div class="mt-10"></div>

        <notes :notes="ticket.notes"
               :can_create_note="ticket.authorizations.can_create_note"
               :ticket_id="ticket.id"
        ></notes>

      </div>

    </div>
  </div>
</template>

<script>
import Notes from "../notes/Notes";

export default {
  name: "TicketMain",
  props: ['ticket'],
  components: {Notes}
}
</script>

<style scoped>

</style>