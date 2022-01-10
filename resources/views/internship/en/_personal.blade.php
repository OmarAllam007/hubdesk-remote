<div class="w-1/2 p-5   rounded-2xl bg-gray-400  font-bold  shadow-md  mb-10 ml-2 ">
    Personal Details:
</div>

<div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
    <div class="w-full ">
        <label class="w-full ">
            {{t('Full Name')}}
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
            {{t('ID Number')}}
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
            {{t('Gender')}}
            <div class="mt-2">
                <label class="inline-flex items-center">
                    <input type="radio" class="form-radio" name="gender" value="1">
                    <span class="ml-2">Male</span>
                </label>
                <label class="inline-flex items-center ml-6">
                    <input type="radio" class="form-radio" name="gender" value="2">
                    <span class="ml-2">Female</span>
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
            {{t('Phone Number')}}
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
            {{t('Email Address')}}
            <input type="text" name="email" value="{{old('email')}}" class="w-full bg-gray-200 appearance-none
        rounded  py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-viola">
        </label>
        @error('email')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="w-full md:w-1/2 px-3 mb-6 md:mb-0 pt-5 ">
    <div class="w-full">
        <label class="w-full ">
            {{t('City of Residence')}}
            <select type="text" name="city"  class="w-full bg-gray-200
        rounded  py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-viola">
                <option value="">Select City</option>
                <option value="Riyadh" @if(old('city') == 'Riyadh' ) selected @endif>Riyadh</option>
                <option value="Jeddah" @if(old('city') == 'Jeddah' ) selected @endif>Jeddah</option>
                <option value="Mecca"  @if(old('city') == 'Mecca' ) selected @endif>Mecca</option>
                <option value="Medina" @if(old('city') == 'Medina' ) selected @endif>Medina</option>
                <option value="Dammam" @if(old('city') == 'Dammam' ) selected @endif>Dammam</option>
                <option value="Khobar" @if(old('city') == 'Khobar' ) selected @endif>Khobar</option>
                <option value="Hufuf"  @if(old('city') == 'Hufuf' ) selected @endif>Hufuf</option>
                <option value="Other"  @if(old('city') == 'Other' ) selected @endif>Other</option>
            </select>
        </label>
        @error('city')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>


<div class="w-full md:w-1/2 px-3 mb-6 md:mb-0 pt-5 ">
    <div class="w-full ">
        <label class="w-full ">
            {{t('Current Address')}}
            <input type="text" name="address" value="{{old('address')}}" class="w-full bg-gray-200 appearance-none
        rounded  py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-viola">
        </label>
        @error('address')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="w-full md:w-1/2 px-3 mb-6 md:mb-0 pt-5">
    <div class="w-full ">
        <label class="w-full ">
            {{t('Interested In')}}
            <div class="mt-2">
                <label class="inline-flex items-center">
                    <input type="radio" class="form-radio" name="interested_in" value="1">
                    <span class="ml-2">Internship (with stipend only)</span>
                </label>
                <label class="inline-flex items-center ml-6">
                    <input type="radio" class="form-radio" name="interested_in" value="2">
                    <span class="ml-2">Internship (without stipend)</span>
                </label>

                <label class="inline-flex items-center ml-6">
                    <input type="radio" class="form-radio" name="interested_in" value="3">
                    <span class="ml-2">No preference</span>
                </label>
            </div>
        </label>
        @error('interested_in')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>