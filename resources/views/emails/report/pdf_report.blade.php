<style>
    .page-break {
        page-break-after: always;
    }
</style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

@if(collect($columns)->count())
        <div >
            <section class="">
                <table >
                    <thead>
                    <tr>
                        @foreach($columns as $column)
                            <th>{{ $column }}</th>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($data->take(10) as $item)
                        <tr>
                            @foreach($item as $cell)
                                <td>{{$cell}}</td>
                            @endforeach
                        </tr>
                    @endforeach
                    <div class="page-break"></div>

                    </tbody>
                </table>

            </section>
        </div>
    @endif
