<!DOCTYPE html>
<html>
<head>
    <title>{{$report->title}}</title>
    <style>
        @media print{@page {size: landscape}}

        @page {
            footer: page-footer;
            margin: 0;
            margin-top: 35pt;
            margin-bottom: 50pt;
            margin-footer: 18pt;
        }

        @page :first {
            margin-top: 0;
        }

        body {
            margin: 0;
            font-family: sans-serif;
            font-size: 12pt;
        }

        table, tr, td {
            padding: 0;
            border-collapse: collapse;
        }

        table {
            width: 100%;
        }

        td {
            vertical-align: top;
        }

        .page-break-before {
            page-break-before: always;
        }

        .container {
            padding: 0 35pt;
        }

        main .container {
            margin-top: 2em;
        }

        main h2 {
            margin: 0 0 .8em;
            page-break-after: avoid;
        }

        main p, main .table-wrapper {
            margin: 0 0 1em;
        }

        .col3 {
            width: 25%;
        }

        .col9 {
            width: 75%;
            padding: 5px;
        }

        #header {
            border: none;
            padding: 30pt 0;
            border-bottom: 1px lightgray dashed;
            background-color: white;
        }

        #header table {
            color: #FFFFFF;
        }

        .grid-images {
            margin: -1%;
        }

        .tile-image {
            float: left;
            padding: 1%;
        }

        .tile-image img {
            display: block;
            width: 100%;
        }

        .details-column-table {
            margin: 0 15pt;
            table-layout: fixed;
        }

        .details-column-table tr {
            border: none;
            border-bottom: .5pt solid #ddd;
        }

        .details-column-table tr:last-child {
            border: none;
        }

        .details-column-table td {
            line-height: 30pt;
        }

        .details-column-table .label {
            font-weight: bold;
        }

        .details-column-table .value {
            text-align: right;
            white-space: nowrap;
            font-weight: normal;
        }
    </style>
</head>
<body>
<header id="header">
    <div class="container">
        <div class="table-wrapper">
            <table>
                <tr>
                    <td class="col9">
                        <h1 style="font-size: 1.6em; margin-bottom: 10pt;color: black">{{$report->title}}</h1>
                        <p style="color: black">Generated By: {{auth()->check() ? auth()->user()->name : 'Hubdesk System' }}</p>
                        <p style="color: black">Generated at : {{Carbon\Carbon::now()->toDateTimeString()}}</p>
                    </td>
                    <td class="col3" style="text-align: right; vertical-align: middle;"><img alt="Hubdesk Logo" src="{{asset('images/logo.png')}}"></td>
                </tr>
            </table>
        </div>
    </div>
</header>
<main>
    <div class="container">
{{--        <h2>Details</h2>--}}
        <table>
            <tr>
{{--                <td style="font-size: 8pt;font-weight: bold;text-align: center;border: 1px solid lightgray">ID</td>--}}
                @foreach($columns as $column)
                    <td style="font-size: 8pt;font-weight: bold;text-align: center;border: 1px solid lightgray;background-color: #0079b4;color: white;">
                        {{ $column }}
                    </td>
                @endforeach
            </tr>


            @foreach($data as $key=>$row)
                <tr style="page-break-inside:avoid; page-break-after:auto">
{{--                    <td style="font-size: 6pt;text-align: center;border: 1px solid lightgray">{{$key+1}}</td>--}}
                    @foreach($row as $cell)
                        <td style="font-size: 6pt;text-align: center;border: 1px solid lightgray">{!! $cell !!}</td>
                    @endforeach
                </tr>

            @endforeach


        </table>
    </div>
    {{--    <div class="page-break-before"></div>--}}
</main>
</body>
</html>