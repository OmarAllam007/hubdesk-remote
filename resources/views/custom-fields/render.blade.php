
@if ($category )
    @php
    $category = \App\Category::find($category['id']);
    @endphp
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

@if (isset($item))
    @foreach($item->custom_fields->sortBy('label')->groupBy('label')->sortBy('label') as $key=>$Ifields)
        @if($key != '')
            <div class="col-md-12">
                <div class="fields_label">
                    <strong>{{t($key)}}</strong>
                </div>
                <br>
            </div>
        @endif
        {{--        {{dd($subfields->sortBy('order') )}}--}}
        @foreach($Ifields->sortBy('order') as $field)
            <div class="col-sm-6">
                @include('custom-fields.' . $field['type'], compact('field'))
            </div>
        @endforeach
    @endforeach
@endif