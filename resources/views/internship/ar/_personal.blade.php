<div class="w-1/2 p-5   rounded-2xl bg-gray-400  font-bold  shadow-md  mb-10 ml-2 ">
    المعلومات الشخصية
</div>

<div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
    <div class="w-full ">
        <label class="w-full ">
            {{t('الإسم')}}
            <input type="text" name="full_name" value="{{old('full_name')}}" class="w-full bg-gray-200 appearance-none
        rounded  py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-viola">
        </label>
        @error('full_name')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="w-full md:w-1/2 px-3 mb-6 md:mb-0 pt-5 ">
    <div class="w-full ">
        <label class="w-full ">
            {{t('رقم الهوية')}}
            <input type="text" name="id_number" value="{{old('id_number')}}" class="w-full bg-gray-200 appearance-none
        rounded  py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-viola">
        </label>
        @error('id_number')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="w-full md:w-1/2 px-3 mb-6 md:mb-0 pt-5">
    <div class="w-full ">
        <label class="w-full ">
            النوع
            <div class="mt-2">
                <label class="inline-flex items-center">
                    <input type="radio" class="form-radio" name="gender" value="1">
                    <span class="ml-2">ذكر</span>
                </label>
                <label class="inline-flex items-center ml-6">
                    <input type="radio" class="form-radio" name="gender" value="2">
                    <span class="ml-2">أنثى</span>
                </label>
            </div>
        </label>
        @error('gender')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="w-full md:w-1/2 px-3 mb-6 md:mb-0 pt-5 ">
    <div class="w-full ">
        <label class="w-full ">
            رقم الجوال
            <input type="text" name="phone" value="{{old('phone')}}" class="w-full bg-gray-200 appearance-none
        rounded  py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-viola">
        </label>
        @error('phone')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="w-full md:w-1/2 px-3 mb-6 md:mb-0 pt-5 ">
    <div class="w-full ">
        <label class="w-full ">
            البريد الإلكتروني
            <input type="text" name="email" value="{{old('email')}}" class="w-full bg-gray-200 appearance-none
        rounded  py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-viola">
        </label>
        @error('email')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="w-full md:w-1/2 px-3 mb-6 md:mb-0 pt-5 ">
    <div class="w-full ">
        <label class="w-full ">
            العنوان الحالي
            <input type="text" name="address" value="{{old('address')}}" class="w-full bg-gray-200 appearance-none
        rounded  py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-viola">
        </label>
        @error('address')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="w-full md:w-1/2 px-3 mb-6 md:mb-0 pt-5 ">
    <div class="w-full">
        <label class="w-full ">
            مدينة الإقامة
            <select type="text" name="city" id="citySelection" class="w-full bg-gray-200
        rounded  py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-viola">
                <option value="">اختر المدينة</option>
                <option value="Riyadh" @if(old('city') == 'Riyadh' ) selected @endif>الرياض</option>
                <option value="Jeddah" @if(old('city') == 'Jeddah' ) selected @endif>جدة</option>
                <option value="Mecca" @if(old('city') == 'Mecca' ) selected @endif>مكة</option>
                <option value="Medina" @if(old('city') == 'Medina' ) selected @endif>المدينة المنورة</option>
                <option value="Dammam" @if(old('city') == 'Dammam' ) selected @endif>الدمام</option>
                <option value="Khobar" @if(old('city') == 'Khobar' ) selected @endif>الخبر</option>
                <option value="Hufuf" @if(old('city') == 'Hufuf' ) selected @endif>الهفوف</option>
                <option value="Other" @if(old('city') == 'Other' ) selected @endif>أخرى</option>
            </select>
        </label>
        @error('city')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="w-full md:w-1/2 px-3 mb-6 md:mb-0 pt-5 hidden" id="other_city">
    <div class="w-full ">
        <label class="w-full ">
            {{t('اسم المدينة')}}
            <input type="text" name="other_city" value="{{old('other_city')}}" class="w-full bg-gray-200 appearance-none
        rounded  py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-viola">
        </label>
        @error('other_city')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="w-full px-3 mb-6 md:mb-0 pt-5">
    <div class="w-full ">
        <label class="w-full ">
            {{t('الأفضلية في العمل')}}
            <div class="mt-2">
                <label class="inline-flex items-center">
                    <input type="radio" class="form-radio" name="work_preference" value="4" checked>
                    <span class="ml-2">لا يوجد تفضيل معين</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="radio" class="form-radio" name="work_preference" value="1">
                    <span class="ml-2">من المكتب</span>
                </label>
                <label class="inline-flex items-center ml-6">
                    <input type="radio" class="form-radio" name="work_preference" value="2">
                    <span class="ml-2">عمل ميداني</span>
                </label>

                <label class="inline-flex items-center ml-6">
                    <input type="radio" class="form-radio" name="work_preference" value="3">
                    <span class="ml-2">العمل عن بعد</span>
                </label>


            </div>
        </label>
        @error('interested_in')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>