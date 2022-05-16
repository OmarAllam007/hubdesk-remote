<template>
  <div>
    <div class="flex flex-col  bg-gray-200 w-full h-auto rounded-xl pt-5 justify-center" @dragover="dragover"
         @dragleave="dragleave" @drop="drop">
      <div class="flex justify-center w-full">
        <i class="fa fa-5x fa-cloud-upload text-gray-700 "></i>
      </div>
      <div class="flex justify-center w-full p-12">

        <input type="file" multiple name="fields[assetsFieldHandle][]" id="assetsFieldHandle"
               class="w-px h-px opacity-0 overflow-hidden absolute" @change="onChange" ref="file"
               accept=".pdf,.jpg,.jpeg,.png,.docx,.xlsx,.xls"/>

        <label for="assetsFieldHandle" class="block cursor-pointer" dir="rtl">
          <span>
            {{ $root.t('Drag and drop or') }}
            {{ $root.t('or') }} <span class="underline text-blue-600">{{ $root.t('Click here')}}</span> {{ $root.t('to upload their files')}}
          </span>
        </label>

      </div>
      <div class="flex  ">
        <ul class="w-full m-5 " v-if="this.fileList.length" v-cloak>
          <li class="flex justify-between text-md font-bold bg-gray-400 py-5 px-5 hover:bg-gray-400 rounded-xl mt-2 shadow-md"
              v-for="file in fileList">
            {{ file.name }}
            <button class="text-black" type="button" @click="remove(fileList.indexOf(file))"
                    title="Remove file"><i class="fa fa-close fa-lg text-red-600"></i>
            </button>
          </li>
        </ul>
      </div>
      <p v-if="errors !== '' " class="font-bold p-5 text-red-700">{{ errors }}</p>
    </div>
  </div>
</template>

<script>
export default {
  name: "TicketFormAttachments",
  props:['errors'],
  data() {
    return {
      fileList: [] // Store our uploaded files
    }
  },
  methods: {
    onChange() {
      this.fileList.push(...this.$refs.file.files)
      this.$parent.$data.form.files.push(...this.$refs.file.files)
    },
    remove(i) {
      this.fileList.splice(i, 1);
      this.$parent.$data.form.files.splice(i, 1);
    },
    dragover(event) {
      event.preventDefault();
      // Add some visual fluff to show the user can drop its files
      if (!event.currentTarget.classList.contains('bg-gray-400')) {
        event.currentTarget.classList.remove('bg-gray-100');
        event.currentTarget.classList.add('bg-gray-400');
      }
    },
    dragleave(event) {
      // Clean up
      event.currentTarget.classList.add('bg-gray-100');
      event.currentTarget.classList.remove('bg-gray-400');
    },
    drop(event) {
      event.preventDefault();
      this.$refs.file.files = event.dataTransfer.files;
      this.onChange(); // Trigger the onChange event manually
      // Clean up
      event.currentTarget.classList.add('bg-gray-100');
      event.currentTarget.classList.remove('bg-gray-400');
    }
  }
}
</script>

<style scoped>
[v-cloak] {
  display: none;
}
</style>