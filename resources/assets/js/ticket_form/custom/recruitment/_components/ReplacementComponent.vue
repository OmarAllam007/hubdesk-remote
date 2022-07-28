<template>
  <div>
    <div class="flex flex-col ">
      <label
          class="font-semibold">
        {{ $root.t(label) }} <span v-if="required == '1'" class="text-red-700 font-bold">*</span>
      </label>

      <v-select
          :options="users" label="label" v-model="searchedUser" id="requester_id" name="requester_id"
          placeholder="Replacement ID"
          :disabled="loadingFromSAP"
          class="selection-list bg-white " @search="loadUsers"

      ></v-select>
      <span v-if="loadingFromSAP"><i class="fa fa-spinner fa-spin fa-xl"></i> <span class="text-sm ">Loading from SAP</span></span>

      <br>
      <table v-if="sapUser && !loadingFromSAP && sapUser.en_name != ''" class="shadow-md ">
        <tr >
          <td class="bg-gray-300 text-gray-800  uppercase  leading-normal py-3   px-6 text-left">Replacement Name</td>
          <td class="py-3   px-6 text-left">{{sapUser.en_name}}</td>
        </tr>
        <tr >
          <td class="bg-gray-300 text-gray-800  uppercase  leading-normal py-3   px-6 text-left">Replacement Position</td>
          <td class="py-3   px-6 text-left">{{sapUser.en_occupation ? sapUser.en_occupation : 'N/A' }}</td>
        </tr>
        <tr >
          <td class="bg-gray-300 text-gray-800  uppercase  leading-normal py-3   px-6 text-left">Date of Join</td>
          <td class="py-3   px-6 text-left">{{sapUser.date_of_join}}</td>
        </tr>
        <tr v-if="!sapUser.is_active" >
          <td class="bg-gray-300 text-gray-800  uppercase  leading-normal py-3   px-6 text-left">Separation date</td>
          <td class="py-3   px-6 text-left">{{sapUser.end_of_service_date}}</td>
        </tr>
      </table>

    </div>
  </div>


</template>

<script>
import "selectize/dist/css/selectize.bootstrap3.css";
import 'vue-select/dist/vue-select.css';
import vSelect from "vue-select";
import Loader from "../../../../ticket/_components/Loader";
import {EventBus} from "../../../../EventBus";

export default {
  name: "ReplacementComponent",
  props: ['id','label', 'required','item_id'],
  data() {
    return {
      users: [],
      searchedUser: '',
      sapUser: '',
      loadingFromSAP: false,
    }
  },
  created() {
    this.loadUsers();
  },
  methods: {
    loadUsers(searchText = '') {
      axios.get(`/list/employees?search=${searchText}`).then((response) => {
        this.users = response.data;
        // console.log(this.users.length)
        // if (searchText != '') {
        //   this.requester = response.data[0]
        // }
      });
    },

    loadFromSAP(sapID) {
      this.loadingFromSAP = true
      axios.get(`/list/sap-info?id=${sapID}`).then((response) => {
        this.loadingFromSAP = false
        this.sapUser =  response.data;

        EventBus.$emit('replacement-user-from-sap',{id:this.item_id,value:this.sapUser})
      }).catch((e)=>{
        this.loadingFromSAP = false
      });
    }
  },
  watch: {
    searchedUser(value) {
      this.loadFromSAP(value.employee_id);
    }
  },
  components: {vSelect,Loader}
}
</script>

<style scoped>

</style>