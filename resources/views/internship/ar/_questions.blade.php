<div class="w-1/2 p-5   rounded-2xl bg-gray-400  font-bold  shadow-md  mb-10 ml-2 ">
    أسئلة أخرى
</div>

<div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
    <div class="w-full ">
        <label class="w-full ">
            هل توجد خبرة سابقة في تدريب صيفي أو تعاوني ؟
            <select type="text" name="previous_training" class="w-full bg-gray-100  border-2
        rounded  py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-viola">
                <option value="">اختر</option>
                <option value="Yes" @if(old('previous_training') == 'Yes' ) selected @endif>نعم</option>
                <option value="No"  @if(old('previous_training') == 'No' ) selected @endif>لا</option>
            </select>
        </label>
        @error('previous_training')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="w-full pt-5 ">
        <label class="w-full ">
            الموقع / المدينة المفضلة للتدريب
            <input type="text" name="preferred_location" value="{{old('preferred_location')}}"  class="w-full bg-gray-100  border-0
        rounded  py-2 px-4 text-gray-700
         focus:outline-none focus:bg-white focus:border-viola rounded-md ">
        </label>
        @error('preferred_location')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="w-full pt-5 ">
        <label class="w-full ">
             لماذا تريد القيام بالتدريب  مع شركة الكفاح؟
            <textarea  name="reason"  class="w-full bg-gray-100  border-0
        rounded  py-2 px-4 text-gray-700
         focus:outline-none focus:bg-white focus:border-viola rounded-md " cols="30" rows="10">{{old('reason')}}</textarea>
        </label>
        @error('reason')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

</div>
