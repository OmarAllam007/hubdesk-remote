@if ($category )
    @foreach($category->custom_fields->sortBy('label')->groupBy('label') as $key=>$sFields)
        @if($key != '')
            <div class="col-md-12">
                <div class="fields_label">
                    <strong>{{t($key)}}</strong>
                </div>
                <br>
            </div>
        @endif
        @foreach($sFields->sortBy('order') as $field)
            <div class="col-sm-6">
                @include('custom-fields.' . $field['type'], compact('field'))
            </div>
        @endforeach
    @endforeach
@endif

@if (isset($subcategory))
    @foreach($subcategory->custom_fields->sortBy('label')->groupBy('label')->sortBy('label') as $key=>$subfields)
        @if($key != '')
            <div class="col-md-12">
                <div class="fields_label">
                    <strong>{{t($key)}}</strong>
                </div>
                <br>
            </div>
        @endif
{{--        {{dd($subfields->sortBy('order') )}}--}}
        @foreach($subfields->sortBy('order') as $field)
            <div class="col-sm-6">
                @include('custom-fields.' . $field['type'], compact('field'))
            </div>
        @endforeach
    @endforeach
@endif

@if (isset($item) && count($item->custom_fields))
    @foreach($item->custom_fields as $field)
        <div class="col-sm-6">
            @include('custom-fields.' . $field['type'], compact('field'))
        </div>
    @endforeach
@endif