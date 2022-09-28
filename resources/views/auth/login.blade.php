@extends('layouts.app')

{{--@section('header')--}}
{{--    <h4 class="panel-title">Login</h4>--}}
{{--@endsection--}}
@section('body')

    <div style="width: 100%;display: flex;justify-content: center;align-items: center;padding: 1px">
        <div  class="p-10 rounded-xl  shadow-md  pt-16  mt-16 bg-gradient-to-b   from-gray-300   to-gray-500 ">
            <form style="width: 100%;align-content: center" class="form-horizontal" role="form" method="POST"
                  action="{{ url('/login') }}">
                {!! csrf_field() !!}

                <div class="form-group">
                    <div class="col-sm-12 flex justify-center">
                        <img src="{{asset('images/hubdesk.png')}}" style="width:66.66666667%" alt="">
                    </div>
                </div>
                <br>
                <div class="form-group{{ $errors->has('employee_id') ? ' has-error' : '' }}">
                    <div class="col-sm-12">
                        <input type="text" class="w-full px-4 py-4   mt-2 mr-4 text-base text-black transition duration-500 ease-in-out transform rounded-lg bg-gray-100 focus:border-blueGray-500 focus:bg-white focus:outline-none focus:shadow-outline focus:ring-2 ring-offset-current ring-offset-2" name="employee_id" id="employee_id"
                               value="{{ old('employee_id') }}" placeholder="Employee ID ( 90xxxxxx )"
                        >
                        @if ($errors->has('employee_id'))
                            <span class="error-message">{{ $errors->first('employee_id') }}</span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                    <div class="col-sm-12">
                        <input type="password" class="w-full px-4 py-4   mt-2 mr-4 text-base text-black transition duration-500 ease-in-out transform rounded-lg focus:border-blueGray-500 focus:bg-white focus:outline-none focus:shadow-outline focus:ring-2 " name="password" id="password"
                               placeholder="Password">
                        @if ($errors->has('password'))
                            <span class="error-message">{{ $errors->first('password') }}</span>
                        @endif
                    </div>
                </div>


                <div class="form-group{{ $errors->has('language') ? ' has-error' : '' }}">
                    <div class="col-sm-12">
                        <select name="language" id="language" class="w-full px-4 py-4   mt-2 mr-4 text-base text-black transition duration-500 ease-in-out transform rounded-lg bg-gray-100 focus:border-blueGray-500 focus:bg-white focus:outline-none focus:shadow-outline focus:ring-2 ring-offset-current ring-offset-2">
                            @foreach(App\Language::$LANGUAGES as $key=>$language)
                                <option value="{{$key}}">{{$language}}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('language'))
                            <span class="error-message">{{ $errors->first('language') }}</span>
                        @endif
                    </div>
                </div>


                <div class="form-group">
                    <div class="col-sm-8">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="remember"> Remember Me
                            </label>
                        </div>
                    </div>
                </div>

                <div class="w-full">
                    <button type="submit"
                            class="btn text-white hover:text-white  w-full" style="background-color: #1a1d50; opacity: 0.8  ">
                        <i class="fa fa-btn fa-sign-in"></i> Login
                    </button>


{{--                    <div class="w-full flex justify-center pt-4">--}}
{{--                        <a href="{{route('password.request')}}" class="btn w-full bg-teal-600  text-white hover:text-white "--}}
{{--                           title="Reset password will works only if your email registered on Hubdesk"--}}
{{--                           >--}}
{{--                            <i class="fa fa-btn fa-unlock"></i> Reset Password--}}
{{--                        </a>--}}
{{--                    </div>--}}
                </div>
            </form>
        </div>
    </div>

@endsection
