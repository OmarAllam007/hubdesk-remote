<template>
  <div>
    <div class="flex" v-if="isTechnician === 1">
      <div class="w-full">
        <div class="form-group form-group-sm ">
          <label for="requester_id" class="block tracking-wide text-gray-700 font-bold mb-2">
            {{ $root.t('Requester') }}
          </label>
          <div class="w-1/2">
            <v-select
                :options="users" label="label" v-model="requester" id="requester_id" name="requester_id"
                :placeholder="t('Created For Me')"
                class="selection-list bg-white " @search="searchForUser"

            ></v-select>
          </div>
          <div v-if="showUserDetails && requester !== '' && requester !== undefined" class="pt-5 px-1">
            <div class="flex space-x-5">
              <div>
                <span class=" text-gray-700 font-bold">{{ $root.t('Business Unit') }}:</span>
                <span>{{ requester.business_unit }}</span>
              </div>

              <div>
                <span class=" text-gray-700 font-bold">{{ $root.t('Department') }}:</span>
                <span>{{ requester.department }}</span>
              </div>

              <div>
                <span class=" text-gray-700 font-bold">{{ $root.t('Job') }}:</span>
                <span>{{ requester.job }}</span>
              </div>

              <div v-if="requester.email">
                <span class=" text-gray-700 font-bold">{{ $root.t('Email') }}:</span>
                <span>{{ requester.email }}</span>
              </div>

              <div v-if="showBalanceLbl">
                <span class="text-gray-700 font-bold mb-2">{{ $root.t('Leave Balance') }}:</span>
                <span>{{ requester.extra_fields.balance_an_lv_ent }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import _ from "lodash";
import 'vue-select/dist/vue-select.css';
import vSelect from "vue-select";

export default {
  name: "UsersList",
  props: {
    isTechnician: {
      type: Number,
      required: true,
    },
    auth_user: {
      type: String,
      required: true,
    },
    showUserDetails: {
      type: Boolean,
      default: function () {
        return true
      }
    },
    show_balance: {
      type: Number,
      default: function () {
        return 1
      }
    },

  },
  data() {
    return {
      users: [],
      requester: {},
      requester_id: ''
    }
  },
  created() {
    this.loadUsers(this.auth_user);
  },
  methods: {
    searchForUser(text) {
      if (text.length > 3) {
        this.loadUsers(text);

      }
    },
    loadUsers(searchText = '') {
      axios.get(`/list/employees?search=${searchText}`).then((response) => {
        this.users = response.data;

        if (searchText != ''){
          this.requester = response.data[0]
        }
      });
    },
    t(word) {
      let translation = _.find(this.translations, {'word': word});

      if (translation) {
        return translation.translation;
      }
      return word;
    },
  },
  watch: {
    requester(value) {
      if (value === undefined){
        return false
      }
      this.$props.requester_id = value.id
      this.$emit('requester-changed', {value, selectedOption: value})
    }
  },
  computed:{
    showBalanceLbl(){
      if (this.requester === undefined){
        return false;
      }

      return this.show_balance === 1 && (this.requester.extra_fields && this.requester.extra_fields.balance_an_lv_ent)
    }
  },
  components: {vSelect}
}
</script>

<style scoped>

</style>