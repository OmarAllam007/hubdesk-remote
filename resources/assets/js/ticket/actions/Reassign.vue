<template>
  <div v-if="isReassignOpened">
    <transition name="modal">
      <div class="modal-mask ">
        <div class="modal-wrapper">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" @click="closeModal()"><i class="fa  fa-times"></i></button>
                <h3 class="modal-title text-2xl ">{{ $root.t('Re-assign') }}</h3>
              </div>
              <div class="modal-body">

                <loader class="flex" v-if="loading"></loader>

                <div class="flex" v-else>
                  <div class="w-1/2 ">
                    <div class="flex flex-col">
                      <label for="group_id">Group</label>
                      <select class="border bg-white  rounded-2xl  px-3 py-2 outline-none" id="group_id"
                              name="group_id" v-model="group_id">
                        <option value="" class="py-1">Select Group</option>
                        <option :value="group.id" v-for="group in groups"
                                class="py-1">{{ group.name }}
                        </option>
                      </select>
                    </div>
                    <div class="flex flex-col pt-5 ">
                      <label for="technician_id">{{ $root.t('Technician') }}</label>
                      <select class="border bg-white  rounded-2xl  px-3 py-2 outline-none" id="technician_id"
                              name="technician_id" v-model="technician_id">
                        <option value="" class="py-1">{{ $root.t('Select Technician') }}</option>
                        <option :value="technician.id" v-for="technician in technicians"
                                class="py-1">{{ technician.name }}
                        </option>
                      </select>
                    </div>
                  </div>

                  <div class="w-1/2 ml-5 ">
                    <div class="flex flex-col">
                      <label for="category_id">{{ $root.t('Category') }}</label>
                      <select class="border bg-white rounded-2xl  px-3 py-2 outline-none" id="category_id"
                              name="category_id" v-model="category_id">
                        <option value="" class="py-1">Select Category</option>
                        <option :value="category.id" v-for="category in categories"
                                class="py-1">{{ category.name }}
                        </option>
                      </select>
                    </div>
                    <div class="flex flex-col pt-5">
                      <label for="subcategory_id">{{ $root.t('Subcategory') }}</label>
                      <select class="border bg-white rounded-2xl  px-3 py-2 outline-none" id="subcategory_id"
                              name="subcategory_id" v-model="subcategory_id">
                        <option value="" class="py-1">Select Subcategory</option>
                        <option :value="subcategory.id" v-for="subcategory in subcategories"
                                class="py-1">{{ subcategory.name }}
                        </option>
                      </select>
                    </div>

                    <div class="flex flex-col pt-5" v-if="items.length">
                      <label for="item_id">{{ $root.t('Item') }}</label>
                      <select class="border bg-white rounded-2xl  px-3 py-2 outline-none" id="item_id"
                              name="item_id" v-model="item_id">
                        <option value="" class="py-1">Select Item</option>
                        <option :value="item.id" v-for="item in items"
                                class="py-1">{{ item.name }}
                        </option>
                      </select>
                    </div>

                    <div class="flex flex-col pt-5" v-if="subItems.length">
                      <label for="subitem_id">{{ $root.t('SubItem') }}</label>
                      <select class="border bg-white rounded-2xl  px-3 py-2 outline-none" id="subitem_id"
                              name="subitem_id" v-model="subitem_id">
                        <option value="" class="py-1">Select SubItem</option>
                        <option :value="subItem.id" v-for="subItem in subItems"
                                class="py-1">{{ subItem.name }}
                        </option>
                      </select>
                    </div>

                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button

                    :class="submitClass"
                    class="text-white font-bold py-2 px-4  hover:text-white rounded-xl  mr-2 shadow-md pb-2 mb-2 "
                    @click="reassign">
                  <i class="fa fa-check-circle" v-if="!assigned_loading"></i>
                  <i class="fa fa-spin fa-spinner" v-else></i>
                  {{ $root.t('Reassign') }}
                </button>
                <button type="button"
                        class="bg-white hover:text-gray-800    font-bold py-2 px-4  hover:text-white rounded-xl  mr-2 pb-2 mb-2 "
                        data-dismiss="modal" @click="closeModal()">
                  {{ $root.t('Close') }}
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
import {EventBus} from "../../EventBus";
import axios from 'axios';
import Loader from "../_components/Loader";

export default {
  name: "Reassign",
  components: {Loader},
  props: ['isReassignOpened'],

  data() {
    return {
      group_id: '',
      groups: [],
      technicians: [],
      categories: [],
      subcategories: [],
      items: [],
      subItems: [],
      technician_id: '',
      category_id: '',
      subcategory_id: '',
      item_id: '',
      subitem_id: '',
      ticket_id: '',
      creationState: true,
      loading: false,
      assigned_loading:false,
    }
  },
  created() {

    this.loading = true;
    this.loadData();
    this.ticket_id = this.$parent.ticketData.ticket.id;
    this.group_id = this.$parent.ticketData.ticket.group_id;
    this.technician_id = this.$parent.ticketData.ticket.technician_id;
    this.category_id = this.$parent.ticketData.ticket.category_id;
    this.subcategory_id = this.$parent.ticketData.ticket.subcategory_id;
    this.item_id = this.$parent.ticketData.ticket.item_id;
    this.subitem_id = this.$parent.ticketData.ticket.subitem_id;


    setTimeout(() => {
      this.creationState = false;
      this.loading = false;
    }, 500)

    EventBus.$on('display-reassign-modal', (data) => {

        }
    );
  },
  methods: {
    closeModal() {
      this.$emit('close-reassign-modal');
    },
    loadData() {
      axios.get('/list/technician-groups').then((response) => {
        this.groups = response.data
      });

      axios.get('/list/individual-category').then((response) => {
        this.categories = response.data;
      })
    },
    resetForm() {
      // this.group_id = '';
      // this.technician_id = '';
      // this.category_id = '';
      // this.subcategory_id = '';
      // this.item_id = '';
      // this.technicians = [];
      // this.subcategories = [];
      // this.items = [];
      // this.subItems = [];
    },

    reassign() {
      this.assigned_loading = true;
      axios.post(`reassign/${this.ticket_id}`, {
        'group_id': this.group_id,
        'technician_id': this.technician_id,
        'category_id': this.category_id,
        'subcategory_id': this.subcategory_id,
        'item_id': this.item_id,
        'subitem_id': this.subitem_id
      }).then((response) => {
        EventBus.$emit('send_notification', 'ticket',
            'Ticket Info', `Ticket Reassigned Successfully`, 'success');

        this.$parent.ticketData = response.data.new_ticket;
        this.assigned_loading = false;
        this.resetForm();
        this.closeModal();
      })
    }
  },
  watch: {
    group_id() {
      if (this.group_id) {
        axios.get(`/list/group-technicians/${this.group_id}`).then((response) => {
          this.technicians = response.data
        });
      }

      if (this.creationState) {
        return;
      }

      this.technician_id = '';
      this.technicians = [];
    },
    category_id() {
      if (this.category_id) {
        axios.get(`/list/subcategory/${this.category_id}`).then((response) => {
          this.subcategories = response.data
        });
      }

      if (this.creationState) {
        return;
      }

      this.subcategory_id = '';
      this.item_id = '';
      this.subitem_id = '';
      this.subcategories = [];

    },
    subcategory_id() {
      if (this.subcategory_id) {
        axios.get(`/list/item/${this.subcategory_id}`).then((response) => {
          this.items = response.data
        });
      }

      if (this.creationState) {
        return;
      }

      this.item_id = '';
      this.subitem_id = '';
      this.items = [];
    },

    item_id() {
      if (this.item_id) {
        axios.get(`/list/subitem/${this.item_id}`).then((response) => {
          this.subItems = response.data
        });
      }

      if (this.creationState) {
        return;
      }

      this.subitem_id = '';
      this.subItems = [];

    }
  },
  computed: {
    can_reassign() {
      return this.group_id != '' && this.technician_id != '' && this.category_id != '' && !this.assigned_loading
    },
    submitClass() {
      if (this.can_reassign ) {
        return 'bg-green-600 hover:bg-green-800 cursor-pointer';
      }
      return 'bg-gray-500 hover:bg-gray-500 cursor-not-allowed'
    }
  }
}
</script>

<style scoped>
.modal-mask {
  position: fixed;
  z-index: 9998;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, .5);
  display: table;
  transition: opacity .3s ease;
}

.modal-wrapper {
  display: table-cell;
  vertical-align: middle;
}
</style>