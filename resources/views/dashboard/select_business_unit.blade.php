@extends('layouts.app')

@section('header')
    <h4>Select BusinessUnit</h4>
@endsection

@section('body')
    <section class="col-md-12 card-section">
        <div class=form-group></div>
        <div class="tiles-container">
            @foreach(\App\BusinessUnit::whereHas('categories')->orderBy('name')->get() as $business_unit)
                @if(@can('dashboard') && App\DashboardUser::where('business_unit_id',$business_unit->id)->exists())
                    <a href="{{route('dashboard.display', $business_unit)}}" class="tile">
                        <div class="tile-container" style="display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 250px;
    width: 250px;">
                            <div class="tile-body"
                                 style="width: 100%;height: 100%;display: flex; flex-direction:column;">
                                @if($business_unit->logo)
                                    <p class="text-center logo-animation">
                                        <img src="{{$business_unit->url}}" alt="{{$business_unit->url}}">
                                    </p>
                                @else
                                    <p class="text-center " style="margin-top: 20px;">
                                        {{$business_unit->name}}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </a>
                @endif
            @endforeach
        </div>

    </section>
@endsection