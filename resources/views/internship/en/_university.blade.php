<div class="w-1/2 p-5   rounded-2xl bg-gray-400  font-bold  shadow-md  mb-10 ml-2 ">
    University Details:
</div>

<div class="w-full md:w-1/2 px-3 mb-6 md:mb-0 pt-5 ">
    <div class="w-full ">
        <label class="w-full ">
            College / University Name
            <input type="text" name="university_name" value="{{old('university_name')}}" class="w-full bg-gray-100  appearance-none
        rounded  py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-viola">
        </label>
        @error('university_name')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="w-full md:w-1/2 px-3 mb-6 md:mb-0 pt-5">
    <div class="w-full ">
        <label class="w-full ">
            {{t('Degree Type')}}
            <div class="mt-2">
                <label class="inline-flex items-center">
                    <input type="radio" class="form-radio" name="degree_type" value="1">
                    <span class="ml-2">Diploma</span>
                </label>
                <label class="inline-flex items-center ml-6">
                    <input type="radio" class="form-radio" name="degree_type" value="2">
                    <span class="ml-2">Bachelor</span>
                </label>
            </div>
        </label>
        @error('degree_type')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="w-full md:w-1/2 px-3 mb-6 md:mb-0 pt-5 ">
    <div class="w-full ">
        <label class="w-full ">
            Degree Name (Title)
            <input type="text" name="degree_name" value="{{old('degree_name')}}" class="w-full bg-gray-100  appearance-none
        rounded  py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-viola">
        </label>
        @error('degree_name')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="w-full md:w-1/2 px-3 mb-6 md:mb-0 pt-5 ">
    <div class="w-full ">
        <label class="w-full ">
            Academic Major
            <select type="text" name="discipline" value="{{old('discipline')}}" class="w-full bg-gray-100  border-2
        rounded  py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-viola">
                <option value="">Select</option>
                <option value="Civil" @if(old('discipline') == 'Civil' ) selected @endif>Civil</option>
                <option value="Architecture" @if(old('discipline') == 'Architecture' ) selected @endif>Architecture</option>
                <option value="Mechanical" @if(old('discipline') == 'Mechanical' ) selected @endif>Mechanical</option>
                <option value="Finance" @if(old('discipline') == 'Finance' ) selected @endif>Finance</option>
                <option value="HR" @if(old('discipline') == 'HR' ) selected @endif>HR</option>
                <option value="Other" @if(old('discipline') == 'Other' ) selected @endif>Other</option>
            </select>
        </label>
        @error('discipline')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="w-full md:w-1/2 px-3 mb-6 md:mb-0 pt-5 ">
    <div class="w-full ">
        <label class="w-full ">
            Expected Year of Graduation
            <input type="month"  name="expected_graduation_year" value="{{old('expected_graduation_year')}}" class="w-full bg-gray-100  appearance-none
        rounded  py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-viola">
        </label>
        @error('expected_graduation_year')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
