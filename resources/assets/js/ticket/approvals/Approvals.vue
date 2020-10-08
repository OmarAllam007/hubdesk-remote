<template>
  <div class="col-md-8">
    <!--    @can('submit_approval',$ticket)-->
    <div class="form-group">
      <button @click.prevent="addNewLevel" class="btn btn-sm btn-primary btn-rounded btn-outlined"><i
          class="fa fa-plus"></i> Add New Level
      </button>
    </div>
    <div v-for="(level, key) of levels">
      <approval-item :level="level" :users="users" :index="key" :key="key"></approval-item>
    </div>
    <!--    @if($approvalLevels->count())-->
    <!--    @foreach($approvalLevels as $key=>$approvalLevel)-->
    <!--    <livewire:approval.approval-item :level="$approvalLevel" :users="$users" :key="$key" :index="$key"-->
    <!--                                     :showStage="$approvalLevels->count() && $key > 1">-->

    <!--      {{ // &#45;&#45;@livewire('approval.approval-item')&#45;&#45; }}-->
    <!--      @endforeach-->
    <!--      @endif-->
    <div class="form-group">
      <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i>Send</button>
    </div>
    <!--      @endcan-->

  </div>
</template>

<script>
import axios from "axios";
import ApprovalItem from "./ApprovalItem";
import {EventBus} from "../../EventBus";

export default {
  name: "Approvals",
  data() {
    return {
      levels: [],
      users: [],
    }
  },
  created() {


    EventBus.$on('remove-approval-item', (index) => {
      this.removeLevel(index);
    })
  },
  mounted() {
    this.addNewLevel();
    axios.get('/list/approvers').then((response) => {
      this.users = response.data;
    })
  },

  methods: {
    addNewLevel() {
      this.levels.push({});
    },
    removeLevel(index) {
      this.levels.splice(index, 1)
    }
  },
  components: {ApprovalItem}
}
</script>

<style scoped>

</style>