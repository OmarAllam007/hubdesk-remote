<div class="w-1/2 p-5   rounded-2xl bg-gray-400  font-bold  shadow-md  mb-10 ml-2 ">
    تفاصيل التدريب
</div>

{{--<div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">--}}
{{--    <div class="w-full ">--}}
{{--        <label class="w-full ">--}}
{{--            صيفي أم تعاوني ؟--}}
{{--            <label class="md:w-2/3 block font-bold">--}}
{{--                <input class="mr-2 leading-tight" type="checkbox" value="Summer" name="type[]" id="type[]">--}}
{{--                <span class="text-md ">--}}
{{--                    صيفي--}}
{{--                </span>--}}
{{--            </label>--}}

{{--            <label class="md:w-2/3 block font-bold">--}}
{{--                <input class="mr-2 leading-tight" type="checkbox" value="Co-op" name="type[]" id="type[]">--}}
{{--                <span class="text-md ">--}}
{{--                    تعاوني--}}
{{--                </span>--}}
{{--            </label>--}}
{{--        </label>--}}
{{--        @error('type')--}}
{{--        <div class="text-danger">{{ $message }}</div>--}}
{{--        @enderror--}}
{{--    </div>--}}
{{--</div>--}}

<div class="w-full md:w-1/2 px-3 mb-6 md:mb-0 pt-5 ">
    <div class="w-full ">
        <label class="w-full ">
            المدة
            <input type="text" name="duration" value="{{old('duration')}}" class="w-full bg-gray-100  appearance-none
        rounded  py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-viola">
        </label>

        @error('duration')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="w-full md:w-1/2 px-3 mb-6 md:mb-0 pt-5 ">
    <div class="w-full ">
        <label class="w-full ">
            تاريخ بداية التدريب
            <input type="date" name="start_date" value="{{old('start_date')}}" class="w-full bg-gray-100  appearance-none
        rounded  py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-viola">
        </label>
        @error('start_date')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="w-full md:w-1/2 px-3 mb-6 md:mb-0 pt-5 ">
    <div class="w-full ">
        <label class="w-full ">
            تاريخ نهاية التدريب
            <input type="date" min="2021-11-01" name="end_date" value="{{old('end_date')}}" class="w-full bg-gray-100  appearance-none
        rounded  py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-viola">
        </label>
        @error('end_date')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="w-full md:w-1/2 px-3 mb-6 md:mb-0 pt-5 ">
    <div class="w-full ">
        <label class="w-full ">
            الموعد النهائي للجامعة لموافقة الشركة
            <input type="date" name="deadline" value="{{old('deadline')}}" class="w-full bg-gray-100  appearance-none
        rounded  py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-viola">
        </label>
        @error('deadline')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>


{{--<div class="w-full md:w-1/2 px-3 mb-6 md:mb-0 pt-5 ">--}}
{{--    <div class="w-full ">--}}
{{--        <label class="w-full ">--}}
{{--            خطة التدريب مطلوبة للجامعة؟--}}
{{--            <select type="text" name="training_required" value="{{old('training_required')}}" class="w-full bg-gray-100  border-2--}}
{{--        rounded  py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-viola">--}}
{{--                <option value="">اختر</option>--}}
{{--                <option value="Yes" @if(old('training_required') == 'Yes' ) selected @endif>نعم</option>--}}
{{--                <option value="No" @if(old('training_required') == 'No' ) selected @endif>لا</option>--}}
{{--            </select>--}}
{{--        </label>--}}
{{--        @error('training_required')--}}
{{--        <div class="text-danger">{{ $message }}</div>--}}
{{--        @enderror--}}
{{--    </div>--}}
{{--</div>--}}

{{--<div class="w-full md:w-1/2 px-3 mb-6 md:mb-0 pt-5 ">--}}
{{--    <div class="w-full">--}}
{{--        <label class="w-full ">--}}
{{--            ملاحظات--}}
{{--            <textarea name="remarks" id="remarks" cols="30" rows="10" class="w-full bg-gray-100  appearance-none--}}
{{--        rounded  py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-viola">{{old('remarks')}}</textarea>--}}
{{--        </label>--}}
{{--    </div>--}}
{{--</div>--}}



