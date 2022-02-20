@extends('layouts.app')

@section('body')
    <div class="flex">
        <div class="w-full flex">

            <div class="w-1/4 m-5 ">
                <div class="flex justify-start">
                    <a href="{{route('internship.en')}}" class="bg-transparent hover:bg-gray-500  text-gray-700
        font-semibold hover:text-white py-2 px-4 border border-gray-500 hover:border-transparent rounded-full ">English</a>
                </div>
            </div>
            <div class="w-1/2">
                <div class="flex-col">
                    <div class="flex justify-center">
                        <div class="w-full">
                            <p class="flex justify-center p-5 m-5 bg-viola
                text-white text-3xl rounded-3xl shadow-md">
                                {{t('طلب تدريب')}}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-1/4">
            </div>

        </div>
    </div>
    @if(session()->has('request_send'))
        <div class="flex ">
            <div class="w-full">
                <p class="p-5 m-5 bg-success font-bold text-center text-2xl shadow-lg rounded-lg ">
                    تم إرسال طلب التدريب بنجاح
                </p>
            </div>
        </div>
    @endif
    <form class="w-full p-5 " method="post" action="{{route('internship.post')}}?lang=ar" enctype="multipart/form-data"
          dir="rtl">
        @csrf
        <div class="flex flex-col  flex-wrap -mx-3 mb-6">
            @include('internship.ar._personal')
            <p class="py-5 "></p>
            @include('internship.ar._university')
            <p class="py-5 "></p>
            @include('internship.ar._internship')
            <p class="py-5 "></p>
            @include('internship.ar._files')
            <p class="py-5 "></p>
            @include('internship.ar._questions')
        </div>
        <div class="flex w-1/6    ">
            <button class="w-full bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">
                إرسال الطلب
            </button>
        </div>
    </form>
@endsection

@section('javascript')
    <script>
        var today = new Date().toISOString().split('T')[0];
        document.getElementsByName("end_date")[0].setAttribute('min', today);
        document.getElementsByName("start_date")[0].setAttribute('min', today);
        document.getElementsByName("deadline")[0].setAttribute('min', today);

        $('#citySelection').change(function () {
            if (this.value == 'Other') {
                $('#other_city').removeClass('hidden');
            }else{
                $('#other_city').addClass('hidden');

            }
        })

        $('#academicMajor').change(function () {
            if (this.value == 'Other') {
                $('#other_degree_name').removeClass('hidden');
            }else{
                $('#other_degree_name').addClass('hidden');

            }
        })
    </script>
@endsection