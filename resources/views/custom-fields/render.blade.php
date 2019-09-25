@if ($category )
    @foreach($category->custom_fields as $field)
        <div class="col-sm-6">
        @include('custom-fields.' . $field['type'], compact('field'))
        </div>
    @endforeach
@endif

@if ($subcategory )
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