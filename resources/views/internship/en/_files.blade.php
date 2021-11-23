<div class="w-1/2 p-5   rounded-2xl bg-gray-400  font-bold  shadow-md  mb-10 ml-2 ">
    {{t('Attachments')}}:
</div>

<div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
    <div class="w-full ">
        <label class="w-full ">
            {{t('CV')}}
            <input type="file" name="cv"  class="w-full bg-gray-100  border-0
        rounded  py-2 px-4 text-gray-700
         focus:outline-none focus:bg-white focus:border-viola rounded-md ">
        </label>
        @error('cv')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="w-full pt-5 ">
        <label class="w-full ">
            {{t('University Letter')}}
            <input type="file" name="letter"  class="w-full bg-gray-100  border-0
        rounded  py-2 px-4 text-gray-700
         focus:outline-none focus:bg-white focus:border-viola rounded-md ">
        </label>
        @error('letter')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
