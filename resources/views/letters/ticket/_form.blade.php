<div id="letters" class="pt-10">
    @php
        $language =\Session::get('personalized-language' . \Auth::user()->id, \Config::get('app.locale'));
    @endphp

    <letter-form :item="{{json_encode($item)}}"
                 :groups="{{$groups}}"
                 :priorities="{{$priorities}}"
                 :subject="{{json_encode($subject)}}"
                 :translations="{{json_encode(\App\Translation::where('language',$language)
                                   ->where('view',\App\Translation::FOR_LETTER_VIEW)->get(['word','translation']))}}"
                 :language="{{json_encode($language)}}"
                 :iban="{{json_encode(auth()->user()->business_unit->iban ?? config('letter.iban'))}}"
                 :bank_name="{{json_encode(auth()->user()->business_unit->bank_name1 ?? 'SABB')}}"
                :is-technician="{{json_encode(Auth::user()->isSupport() ? 1 : 0)}}"
    ></letter-form>


    {{--    <div class="row">--}}
    {{--        <div class="col-md-6">--}}
    {{--            @if($errors->count())--}}
    {{--                <div class="alert alert-danger">--}}
    {{--                    <ul>--}}
    {{--                        @foreach($errors->all() as $error)--}}
    {{--                            <li>{{$error}}</li>--}}
    {{--                        @endforeach--}}
    {{--                    </ul>--}}
    {{--                </div>--}}
    {{--            @endif--}}
    {{--        </div>--}}
    {{--    </div>--}}

    {{--    <div class="row">--}}
    {{--        <div class="col-sm-6">--}}
    {{--            <div class="form-group form-group-sm {{$errors->has('subject')? 'has-error' : ''}}">--}}
    {{--                {{ Form::label('subject', t('Subject'), ['class' => 'control-label']) }}--}}
    {{--                {{ Form::text('subject', $subject , ['class' => 'form-control']) }}--}}
    {{--                @if ($errors->has('subject'))--}}
    {{--                    <div class="error-message">{{$errors->first('subject')}}</div>--}}
    {{--                @endif--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </div>--}}

    {{--    <div class="row">--}}


    {{--    </div>--}}

    {{--    <div class="row">--}}
    {{--        <div class="col-sm-7">--}}
    {{--            <div class="form-group form-group-sm {{$errors->has('description')? 'has-error' : ''}}">--}}
    {{--                {{ Form::label('description', t('Description'), ['class' => 'control-label']) }}<strong--}}
    {{--                        class="text-danger">*</strong>--}}
    {{--                {{ Form::textarea('description', null, ['class' => 'form-control richeditor']) }}--}}

    {{--                @if ($errors->has('description'))--}}
    {{--                    <div class="error-message">{{$errors->first('description')}}</div>--}}
    {{--                @endif--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--        <div class="col-sm-5">--}}
    {{--            {{Form::hidden('category_id',$category->id)}}--}}
    {{--            {{Form::hidden('subcategory_id',$subcategory->id ?? null)}}--}}
    {{--            {{Form::hidden('item_id',$item->id ?? null)}}--}}
    {{--        </div>--}}
    {{--    </div>--}}
    {{--    <div class="row">--}}
    {{--        <div class="col-md-6 {{$errors->has('priority_id')? 'has-error' : ''}}">--}}
    {{--            {{ Form::label('priority_id', t('Priority'), ['class' => 'control-label']) }} <strong--}}
    {{--                    class="text-danger">*</strong>--}}
    {{--            <select name="priority_id" id="priority_id" class="form-control">--}}
    {{--                <option value="">{{t('Select Priority')}}</option>--}}
    {{--                @foreach(App\Priority::all() as $priority)--}}
    {{--                    <option value="{{$priority->id}}"> {{ t($priority->name) }}</option>--}}
    {{--                @endforeach--}}
    {{--            </select>--}}
    {{--        </div>--}}
    {{--    </div>--}}
    {{--    <br>--}}

    {{--    <div class="row">--}}
    {{--        <div class="col-md-6">--}}
    {{--            <strong>{{t('Attachments')}} <span class="text-gray-600">[ {{t('Max Attachments size is 5MB')}} ]</span>--}}
    {{--            </strong>--}}
    {{--            <attachments limit="5"></attachments>--}}
    {{--        </div>--}}
    {{--    </div>--}}

    {{--    <hr class="form-divider">--}}

    {{--</div>--}}
    {{--<div class="row">--}}
    {{--    <div class="col-sm-12">--}}
    {{--        <div class="form-group">--}}
    {{--            <button class="btn btn-success"><i class="fa fa-check"></i> {{t('Submit')}}</button>--}}
    {{--        </div>--}}
    {{--    </div>--}}
</div>

