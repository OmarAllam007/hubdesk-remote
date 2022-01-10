<div class="w-1/2 p-5   rounded-2xl bg-gray-400  font-bold  shadow-md  mb-10 ml-2 ">
    معلومات عن الجامعة
</div>

<div class="w-full md:w-1/2 px-3 mb-6 md:mb-0 pt-5 ">
    <div class="w-full ">
        <label class="w-full ">
            الكلية / الجامعة
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
            نوع الدرجة العلمية
            <div class="mt-2">
                <label class="inline-flex items-center">
                    <input type="radio" class="form-radio" name="degree_type" value="1">
                    <span class="ml-2">دبلومة</span>
                </label>
                <label class="inline-flex items-center ml-6">
                    <input type="radio" class="form-radio" name="degree_type" value="2">
                    <span class="ml-2">بكالوريوس</span>
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
            الدرجة العلمية ( المسمى )
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
            التخصص
            <select type="text" name="discipline" value="{{old('discipline')}}" class="w-full bg-gray-100  border-2
        rounded  py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-viola">
                <option value="">اختر التخصص</option>
                <option value="Civil" @if(old('discipline') == 'Civil' ) selected @endif>هندسة مدنية</option>
                <option value="Architecture" @if(old('discipline') == 'Architecture' ) selected @endif>هندسة معمارية</option>
                <option value="Mechanical" @if(old('discipline') == 'Mechanical' ) selected @endif>هندسة ميكانيكية</option>
                <option value="Finance" @if(old('discipline') == 'Finance' ) selected @endif>المحاسبة</option>
                <option value="HR" @if(old('discipline') == 'HR' ) selected @endif>الموارد البشرية</option>
                <option value="Other" @if(old('discipline') == 'Other' ) selected @endif>أخرى</option>
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
            السنة المتوقعة للتخرج
            <input type="month" name="expected_graduation_year" value="{{old('expected_graduation_year')}}" class="w-full bg-gray-100  appearance-none
        rounded  py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-viola">
        </label>
        @error('expected_graduation_year')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
