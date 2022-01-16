<div class="w-1/2 p-5   rounded-2xl bg-gray-400  font-bold  shadow-md  mb-10 ml-2 ">
    Internship Details:
</div>


<div class="w-full md:w-1/2 px-3 mb-6 md:mb-0 pt-5 ">
    <div class="w-full ">
        <label class="w-full ">
            {{t('Duration')}}
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
            {{t('Internship Start Date')}}
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
            {{t('Internship End Date')}}
            <input type="date" name="end_date" value="{{old('end_date')}}" class="w-full bg-gray-100  appearance-none
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
            {{t('University Deadline for Company Approval, if any')}}
            <input type="date" name="deadline" value="{{old('deadline')}}" class="w-full bg-gray-100  appearance-none
        rounded  py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-viola">
        </label>
        @error('deadline')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>


<div class="w-full md:w-1/2 px-3 mb-6 md:mb-0 pt-5 ">
    <div class="w-full ">
        <label class="w-full ">
            {{t('Any Preference for Location / City to do the internship?')}}
            <select type="text" name="pref_city[]" value="{{old('pref_city[]')}}" class="w-full bg-gray-100  border-2
        rounded  py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-viola" multiple size="20">
                <option value="">Select City</option>
                @foreach(\App\InternshipModel::$en_cities as $key=>$city)
                    <option value="{{$city}}" @if(old('pref_city') == $city ) selected @endif>{{$city}}</option>
                @endforeach
            </select>
        </label>
        @error('pref_city')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="w-full md:w-1/2 px-3 mb-6 md:mb-0 pt-5 ">
    <div class="w-full ">
        <label class="w-full ">
            {{t('Any Preference for Kifah Group of Companies to do the internship?')}}
            <select type="text" name="pref_company[]" value="{{old('pref_company[]')}}" class="w-full bg-gray-100  border-2
        rounded  py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-viola" multiple size="20">
                <option value="">Select Company</option>
                @foreach(\App\InternshipModel::$businessUnits as $businessUnit)
                    <option value="{{$businessUnit}}" @if(old('pref_company') == $businessUnit ) selected @endif >{{$businessUnit}}</option>
                @endforeach
            </select>
        </label>
        @error('pref_city')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

{{--<div class="w-full md:w-1/2 px-3 mb-6 md:mb-0 pt-5 ">--}}
{{--    <div class="w-full">--}}
{{--        <label class="w-full ">--}}
{{--            {{t('Remarks')}}--}}
{{--            <textarea name="remarks" id="remarks"  cols="30" rows="10" class="w-full bg-gray-100  appearance-none--}}
{{--        rounded  py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-viola">{{old('remarks')}}</textarea>--}}
{{--        </label>--}}
{{--    </div>--}}
{{--</div>--}}



