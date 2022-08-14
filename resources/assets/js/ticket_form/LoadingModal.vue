<template>
  <div >
    <transition name="modal" v-if="isLoadingSAPFields">
      <div class="modal-mask" id="LoadingModal">
        <div class="modal-wrapper">
          <div class="modal-dialog modal-md ">
            <div class="modal-content">
              <div class="modal-body">
                <div class="flex justify-center items-center">
                  <i class="fa fa-2x fa-spinner fa-spin"></i>
                  <span class="px-2 "></span>
                  <span>Loading User information</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
import {EventBus} from "../EventBus";

export default {
  name: "LoadingModal",
  data(){
    return {
      isLoadingSAPFields:false
    }
  },
  created() {
    EventBus.$on('emit-loading-sap-on',()=>{
      this.isLoadingSAPFields = true
    })
    EventBus.$on('emit-loading-sap-off',()=>{
      this.isLoadingSAPFields = false
    })
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