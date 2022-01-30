<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$letterTicket->ticket->id}}</title>
    <style>
        @media print {
            body {
                display: table;
                table-layout: fixed;
                height: auto;
                font-family: Arial, Helvetica, sans-serif;
            }

            table > tr > td {
                border: #1b1e21 !important;
            }

            .table-header-cell {
                background-color: #eeecec !important;
            }

            table > tr > td {
                border: #1b1e21 !important;
            }

            .table-header-cell-value {
                background-color: white !important;
            }
        }

        p.lt_text {
            font-size: 14pt  !important;
        }
        span.lt_text {
            font-size: 14pt  !important;
        }

        page {
            display: block;
            margin: 0 auto;
            background-repeat: no-repeat;
            @if(\App\LetterTicket::isApprovedTicket($letterTicket->ticket))
                  background-image: url({{url("/storage/headers/{$letterTicket->header}/image.jpg")}}) !important;
            @endif
                  background-size: contain !important;
        }


        page[size="A4"] {
            width: 21cm;
            height: 29.7cm;
        }

    </style>
    <link rel="stylesheet" href="{{asset('/css/app.css')}}?version={{time()}}">

</head>