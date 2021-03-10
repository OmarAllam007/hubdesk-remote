@extends(request()->has('print') ? 'layouts.print' : 'layouts.app')

@section('stylesheets')
    <style>
        #wrapper {
            background: #eaeaea;
        }

        @media print {
            @page {
                size: A4 landscape;
                margin: 5px;
                padding: 5px;
            }
            table {
                zoom: 0.5;
            }

            #filterBar {
                display: none;
            }

            #ticketPriorityTable {
                width: 100%;
                height: auto;
                overflow: visible;
            }

            #print {
                display: none;
            }

        }
    </style>
@endsection

@section('body')
    <div class="flex  w-full  pl-10 pr-10 pt-10  justify-end ">
        <a href="?print" id="print" class="btn btn-success btn-sm" target="_blank"><i
                    class="fa fa-file"></i> {{ t('PDF') }}</a>
    </div>

    <div class="w-full  p-10" id="status_dashboard">
        <charts :data="{{json_encode($data)}}" :business="{{json_encode($businessUnit->id)}}"></charts>
    </div>
@endsection

@section('javascript')
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.debug.js"></script>
    <script src="{{ asset('js/status_dashboard/index.js') }}"></script>

@endsection