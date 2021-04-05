<template>
  <div>
    <div class="w-full">
      <div class="rounded-xl shadow-md bg-white m-3 pt-3 pl-4 pr-4 pb-4 border-t-4 border-gray-700">
        <p v-html="ticket.description"></p>
      </div>
    </div>
    <div class="w-full">
      <div class="m-3 mt-10">
        <p class="font-bold text-2xl ">{{ $root.t('Request Details') }}</p>
        <div class="w-full mt-3 shadow-md">
          <div class="flex justify-between">
            <div class="w-1/4 p-3 border-viola border-l-4 bg-gray-300  bg-opacity-75  rounded-tl-sm   font-bold">
              {{ $root.t('Category') }}
            </div>
            <div class="w-1/4 p-3 bg-white ">{{ $root.t(ticket.category) }}</div>
            <div class="w-1/4 p-3 border-viola border-l-4 bg-gray-300  bg-opacity-75  font-bold">{{ $root.t('Group') }}</div>
            <div class="w-1/4 p-3 bg-white rounded-tr-xl ">{{ $root.t(ticket.group) }}</div>
          </div>
          <div class="flex justify-between">
            <div class="w-1/4 p-3 border-viola border-l-4 bg-gray-300  bg-opacity-75   font-bold">{{
                $root.t('Subcategory')
              }}
            </div>
            <div class="w-1/4 p-3 bg-white ">{{ $root.t(ticket.subcategory) }}</div>
            <div class="w-1/4 p-3 border-viola border-l-4 bg-gray-300     bg-opacity-75  font-bold"> {{ $root.t('Technician') }}
            </div>
            <div class="w-1/4 p-3 bg-white ">{{ $root.t(ticket.technician) }}</div>
          </div>

          <div class="flex justify-between">
            <div class="w-1/4 p-3 border-viola border-l-4 bg-gray-300  bg-opacity-75   font-bold"> {{ $root.t('Item') }}</div>
            <div class="w-1/4 p-3 bg-white ">{{ $root.t(ticket.item) }}</div>
            <div class="w-1/4 p-3 border-viola border-l-4 bg-gray-300     bg-opacity-75  font-bold"> {{ $root.t('Priority') }}
            </div>
            <div class="w-1/4 p-3 bg-white ">{{ $root.t(ticket.priority) }}</div>
          </div>

          <div class="flex justify-between">
            <div class="w-1/4 p-3 border-viola border-l-4 bg-gray-300  bg-opacity-75   font-bold">{{ $root.t('SubItem') }}</div>
            <div class="w-1/4 p-3 bg-white ">{{ $root.t(ticket.subItem) }}</div>
            <div class="w-1/4 p-3 border-viola border-l-4 bg-gray-300     bg-opacity-75  font-bold">{{ $root.t('Service Cost') }}
            </div>
            <div class="w-1/4 p-3 bg-white ">{{ $root.t(ticket.service_cost) }}</div>
          </div>

          <div class="flex justify-between">
            <div class="w-1/4 p-3 border-viola border-l-4 bg-gray-300  bg-opacity-75   font-bold">{{ $root.t('SLA') }}</div>
            <div class="w-1/4 p-3 bg-white ">{{ $root.t(ticket.sla) }}</div>
            <div class="w-1/4 p-3 border-viola border-l-4 bg-gray-300  bg-opacity-75  font-bold">{{ $root.t('Due Time') }}</div>
            <div class="w-1/4 p-3 bg-white ">{{ ticket.due_date }}</div>
          </div>
        </div>

        <div class="mt-10"></div>

        <div v-if="ticket.fields.length">
          <p class="font-bold text-2xl ">{{ $root.t('Additional Information') }}</p>
          <div class="w-full mt-3 ">
            <div class="flex flex-col shadow-md">
              <div v-for="(field , key) in ticket.fields">
                <div class="flex">
                  <div class="w-1/2 p-3 border-viola border-l-4 bg-gray-300     bg-opacity-75   font-bold">{{ $root.t(field.name) }}
                  </div>
                  <div class="w-1/2 p-3 bg-white ">{{ field.value }}</div>
                </div>
              </div>
            </div>
          </div>
        </div>


        <div class="mt-10"></div>

        <p class="font-bold text-2xl ">{{ $root.t('Requester Details') }}</p>
        <div class="w-full mt-3 shadow-md">
          <div class="flex justify-between">
            <div class="w-1/4 p-3 border-viola border-l-4 bg-gray-300     bg-opacity-75  rounded-tl-sm   font-bold">
              {{ $root.t('Name') }}
            </div>
            <div class="w-1/4 p-3 bg-white ">{{ ticket.requester }}</div>
            <div class="w-1/4 p-3 border-viola border-l-4 bg-gray-300     bg-opacity-75  font-bold">
              {{ $root.t('Business Unit') }}
            </div>
            <div class="w-1/4 p-3 bg-white rounded-tr-xl ">{{ $parent.data.requester.company }}</div>
          </div>

          <div class="flex justify-between">
            <div class="w-1/4 p-3 border-viola border-l-4 bg-gray-300     bg-opacity-75   font-bold">{{ $root.t('Department') }}
            </div>
            <div class="w-1/4 p-3 bg-white ">{{ $parent.data.requester.department }}</div>
            <div class="w-1/4 p-3 border-viola border-l-4 bg-gray-300     bg-opacity-75  font-bold">{{ $root.t('Job title') }}
            </div>
            <div class="w-1/4 p-3 bg-white ">{{ $parent.data.requester.job_title }}</div>
          </div>

          <div class="flex justify-between">
            <div class="w-1/4 p-3 border-viola border-l-4 bg-gray-300     bg-opacity-75   font-bold">{{ $root.t('Email') }}</div>
            <div class="w-1/4 p-3 bg-white ">{{ $parent.data.requester.email }}</div>
            <div class="w-1/4 p-3 border-viola border-l-4 bg-gray-300     bg-opacity-75  font-bold">{{ $root.t('Employee ID') }}
            </div>
            <div class="w-1/4 p-3 bg-white ">{{ $parent.data.requester.employee_id }}</div>
          </div>

          <div class="flex justify-between">
            <div class="w-1/4 p-3 border-viola border-l-4 bg-gray-300  bg-opacity-75   font-bold">
              {{ $root.t('Direct Manager Name') }}
            </div>
            <div class="w-1/4 p-3 bg-white ">{{ $parent.data.requester.manager_name }}</div>
            <div class="w-1/4 p-3 border-viola border-l-4 bg-gray-300     bg-opacity-75  font-bold">
              {{ $root.t('Direct Manager Email') }}
            </div>
            <div class="w-1/4 p-3 bg-white ">{{ $parent.data.requester.manager_email }}</div>
          </div>

          <div class="flex justify-between">
            <div class="w-1/4 p-3 border-viola border-l-4 bg-gray-300     bg-opacity-75   font-bold">{{ $root.t('Mobile') }}</div>
            <div class="w-1/4 p-3 bg-white ">{{ $parent.data.requester.mobile1 }}</div>
            <div class="w-1/4 p-3 border-viola border-l-4 bg-gray-300     bg-opacity-75  font-bold">{{ $root.t('Phone') }}</div>
            <div class="w-1/4 p-3 bg-white ">{{ $parent.data.requester.phone }}</div>
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