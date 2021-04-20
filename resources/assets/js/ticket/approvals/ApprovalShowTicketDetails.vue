<template>
  <div>
    <div>
      <a class="hover:bg-yellow-100 bg-white block rounded-2xl  cursor-pointer" @click="isCollapsed = !isCollapsed">
        <div class="flex  shadow-md  rounded-2xl mb-3 w-full">
          <div class="w-2 rounded-l-md "></div>
          <div class="flex flex-col">
            <div class="shadow-sm ">
              <div class="flex w-full px-2 py-3 ">
                <div class="flex w-1/2"> <!--       name       -->
                  <transition name="slide-fade">
                    <i class="fa fa-chevron-right  p-2 m-2 hover:text-orange-700 " v-if="isCollapsed"></i>
                  </transition>
                  <i class="fa fa-chevron-down  p-2 m-2 hover:text-orange-700" v-if="!isCollapsed"></i>
                  <p class="font-semibold p-2 m-2 text-gray-800 w-full text-lg md:text-xl">Ticket Details
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </a>
      <transition name="fade" mode="out-in">
        <div class="flex flex-col shadow-md mb-5 " v-if="!isCollapsed">

          <div class="p-5 bg-gray-200 shadow-inner shadow-2xl">
            <div class="flex w-full">
              <p class="p-1 rounded-2xl text-center text-2xl pb-3  font-bold">Requester Details</p>
            </div>
            <!--            Requester Details-->
            <div class="flex w-full">
              <div class="w-full mt-3 shadow-md">
                <div class="flex justify-between">
                  <div
                      class="w-1/4 p-3 border-viola border-l-2  bg-gray-300     bg-opacity-75  rounded-tl-sm   font-bold">
                    Name
                  </div>
                  <div class="w-1/4 p-3 bg-white ">{{ requester.name }}</div>
                  <div class="w-1/4 p-3 border-viola border-l-2  bg-gray-300     bg-opacity-75  font-bold">
                    Business Unit
                  </div>
                  <div class="w-1/4 p-3 bg-white rounded-tr-xl ">{{ requester.company }}</div>
                </div>

                <div class="flex justify-between">
                  <div class="w-1/4 p-3 border-viola border-l-2  bg-gray-300     bg-opacity-75   font-bold">
                    Department
                  </div>
                  <div class="w-1/4 p-3 bg-white ">{{ requester.department }}</div>
                  <div class="w-1/4 p-3 border-viola border-l-2  bg-gray-300     bg-opacity-75  font-bold">
                    Job title
                  </div>
                  <div class="w-1/4 p-3 bg-white ">{{ requester.job_title }}</div>
                </div>

                <div class="flex justify-between">
                  <div class="w-1/4 p-3 border-viola border-l-2  bg-gray-300     bg-opacity-75   font-bold">
                    Email
                  </div>
                  <div class="w-1/4 p-3 bg-white ">{{ requester.email }}</div>
                  <div class="w-1/4 p-3 border-viola border-l-2  bg-gray-300     bg-opacity-75  font-bold">
                    Employee ID
                  </div>
                  <div class="w-1/4 p-3 bg-white ">{{ requester.employee_id }}</div>
                </div>

                <div class="flex justify-between">
                  <div class="w-1/4 p-3 border-viola border-l-2  bg-gray-300  bg-opacity-75   font-bold">
                    Direct Manager Name
                  </div>
                  <div class="w-1/4 p-3 bg-white ">{{ requester.manager_name }}</div>
                  <div class="w-1/4 p-3 border-viola border-l-2  bg-gray-300     bg-opacity-75  font-bold">
                    Direct Manager Email
                  </div>
                  <div class="w-1/4 p-3 bg-white ">{{ requester.manager_email }}</div>
                </div>

                <div class="flex justify-between">
                  <div class="w-1/4 p-3 border-viola border-l-2  bg-gray-300     bg-opacity-75   font-bold">
                    Mobile
                  </div>
                  <div class="w-1/4 p-3 bg-white ">{{ requester.mobile1 }}</div>
                  <div class="w-1/4 p-3 border-viola border-l-2  bg-gray-300     bg-opacity-75  font-bold">
                    Phone
                  </div>
                  <div class="w-1/4 p-3 bg-white ">{{ requester.phone }}</div>
                </div>
              </div>
            </div>

            <div class="border border-t-2  border-gray-300 mt-10  "></div>
            <!--      Custom Fields      -->
            <div class="flex w-full pt-5 ">
              <p class="p-1 rounded-2xl text-center text-2xl pb-3   font-bold">Additional Information</p>
            </div>
            <div class="flex w-full shadow-md ">
              <table class="min-w-max w-full table-auto">
                <tr v-for="field in fields">
                  <th class="p-3 border-viola border-l-2  bg-gray-300     bg-opacity-75  rounded-tl-sm   font-bold">
                    {{ field.name }}
                  </th>
                  <th class="p-3 bg-white">{{ field.value }}</th>
                </tr>
              </table>
            </div>

            <div class="border border-t-2  border-gray-300 mt-10"></div>

            <div class="flex w-full pt-5 " v-if="replies.length">
              <p class="p-1 rounded-2xl text-center text-2xl pb-3   font-bold">Replies</p>
            </div>
            <div v-for="reply in replies">
              <reply :reply="reply"></reply>
            </div>

            <div class="border border-t-2  border-gray-300 mt-10"></div>

            <div class="flex w-full pt-5 " v-if="approvals.length">
              <p class="p-1 rounded-2xl text-center text-2xl pb-3 font-bold">Approvals</p>
            </div>

            <div v-for="approval in approvals">
              <approval :approval="approval"></approval>
            </div>
          </div>
        </div>
      </transition>
    </div>
  </div>
</template>

<script>
import Reply from "../conversation/Reply";
import Approval from "../conversation/Approval";

export default {
  name: "ApprovalShowTicketDetails",
  components: {Approval, Reply},
  props: ['requester', 'fields', 'replies', 'approvals'],
  data() {
    return {
      isCollapsed: true
    }
  },
}
</script>

<style scoped>

.slide-fade-enter-active {
  transition: all .5s ease;
}

.slide-fade-leave-active {
  transition: all 0s cubic-bezier(1.0, 0.5, 0.8, 1.0);
}

.slide-fade-enter, .slide-fade-leave-to
  /* .slide-fade-leave-active below version 2.1.8 */
{
  transform: translateY(-10px);
  opacity: 0;
}
</style>