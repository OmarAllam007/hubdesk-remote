@extends(request()->has('print') ? 'layouts.print' : 'layouts.app')

@section('stylesheets')
    <style>
        #wrapper {
            background: #eaeaea;
        }
    </style>
@endsection

@section('body')
    <a id="print" class="btn btn-success btn-sm"><i class="fa fa-file"></i> {{ t('PDF') }}</a>

    <div class="w-full  p-10" id="status_dashboard">
        <charts></charts>
    </div>
@endsection

@section('javascript')
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.debug.js"></script>
    <script src="{{ asset('js/status_dashboard/index.js') }}"></script>

@endsection