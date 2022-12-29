<template>
  <div>
    <div class="w-full">
      <div class="flex justify-center" v-if="loading">
        <div class="flex-col">
          <Loader></Loader>
          <p class="pt-5 " :class="{'animate-pulse': loading}">Loading your information from SAP</p>
        </div>
      </div>
      <div class="flex-col justify-center items-center" v-else>
        <div class="flex justify-center">
          <div class="w-10"></div>
          <div class="flex flex-col justify-center p-10 ">
            <iframe v-for="path in paths" :src="path" height='800' allowfullscreen="" class="pt-5 "></iframe>
          </div>
          <div class="w-10"></div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Loader from "../ticket/_components/Loader";


export default {
  name: "salarySlip",
  components: {Loader},
  created() {
    this.getPayslips();
  },
  data() {
    return {
      paths: [],
      loading: false,
    }
  },
  methods: {
    getPayslips() {
      this.loading = true;
      axios.get('/get-payslip').then((response) => {
        this.paths = response.data
        this.loading = false;
      }).catch((error) => {
        console.log(error)
        this.loading = false;
      })
    }
  }
}
</script>

<style scoped>

</style>