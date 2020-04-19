@extends('layouts.app')

@section('header')
    <h4 class="panel-title">Login</h4>
@endsection
@section('body')
    <div style="width: 100%;display: flex;justify-content: center;align-items: center;padding: 1px">
        <div class="auth-form">
            <form style="width: 100%;align-content: center" class="form-horizontal" role="form" method="POST"
                  action="{{ url('/login') }}">
                {!! csrf_field() !!}

                <div class="form-group">
                    <div class="col-sm-12 text-center">
                        <img src="{{asset('images/hubdesk.png')}}" style="width:66.66666667%" alt="">
                    </div>
                </div>
                <br>
                <div class="form-group{{ $errors->has('employee_id') ? ' has-error' : '' }}">
                    <div class="col-sm-12">
                        <input type="text" class="form-control" name="employee_id" id="employee_id"
                               value="{{ old('employee_id') }}" placeholder="Employee ID ( 90xxxxxx )"
                        >
                        @if ($errors->has('employee_id'))
                            <span class="error-message">{{ $errors->first('employee_id') }}</span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                    <div class="col-sm-12">
                        <input type="password" class="form-control" name="password" id="password"
                               placeholder="Password">
                        @if ($errors->has('password'))
                            <span class="error-message">{{ $errors->first('password') }}</span>
                        @endif
                    </div>
                </div>


                <div class="form-group{{ $errors->has('language') ? ' has-error' : '' }}">
                    <div class="col-sm-12">
                        <select name="language" id="language" class="form-control">
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

                <div class="form-group">
                    <div class="col-sm-12 text-center">
                        <button type="submit" class="btn btn-primary"
                                style="width: 100%;font-size: 14pt;font-weight: bold">
                            <i class="fa fa-btn fa-sign-in"></i> Login
                        </button>

                        {{--<a href="/auth/google" class="btn btn-danger">--}}
                        {{--<i class="fa fa-btn fa-google-plus"></i> Login using Google--}}
                        {{--</a>--}}

                        {{--<a href="{{route('password.request')}}" class="btn btn-success">--}}
                        {{--<i class="fa fa-btn fa-unlock"></i> Reset Password--}}
                        {{--</a>--}}
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
