@extends(request()->has('print') ? 'layouts.print' : 'layouts.app')

@section('stylesheets')
    <style>
        #wrapper {
            background: #eaeaea;
        }
    </style>
@endsection

@section('body')
    <div class="w-full  p-10" id="status_dashboard">
        <charts></charts>
    </div>
@endsection

@section('javascript')
    <script src="{{ asset('js/status_dashboard/index.js') }}"></script>

@endsection