<template>
  <div class="flex justify-center">
    <div class="loader ease-linear rounded-full border-4 border-t-4 border-gray-200 h-24 w-24 mt-64"></div>
  </div>
</template>

<script>
export default {
  name: "Loader"
}
</script>

<style scoped>
.loader {
  border-top-color: #1a1d50;
  -webkit-animation: spinner 1.5s linear infinite;
  animation: spinner 1.5s linear infinite;
}

@-webkit-keyframes spinner {
  0% {
    -webkit-transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
  }
}

@keyframes spinner {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

.searchbtn {
  background: rgba(26, 29, 80, 0.9);
}

.collapse-btn {
  color: rgba(26, 29, 80, 0.9);
  border-color: rgba(26, 29, 80, 0.9);
}

.collapse-btn:hover {
  background: rgba(26, 29, 80, 0.5);
}

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