@if ($category )
    @foreach($category->custom_fields->groupBy('label') as $key=>$value)
        @if($key != '')
            <div class="col-md-12">
                <div class="fields_label">
                    <strong>{{$key}}</strong>
                </div>
                <br>
            </div>
        @endif
        @foreach($value as $field)
            <div class="col-sm-6">
                @include('custom-fields.' . $field['type'], compact('field'))
            </div>
        @endforeach
    @endforeach
@endif

@if (isset($subcategory))
    @foreach($subcategory->custom_fields as $field)
        <div class="col-sm-6">
            @include('custom-fields.' . $field['type'], compact('field'))
        </div>
    @endforeach
@endif

@if (isset($item) && count($item->custom_fields))
    @foreach($item->custom_fields as $field)
        <div class="col-sm-6">
            @include('custom-fields.' . $field['type'], compact('field'))
        </div>
    @endforeach
@endif