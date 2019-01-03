@extends('layouts.app')

@section('header')
    <h4 class="pull-left">{{t('businessUnits')}}</h4>
    <form action="" class="form-inline" method="get">
        <div class="input-group">
            <input class="form-control input-sm" type="search" name="search" id="searchTerm" placeholder="Search"
                   value="{{Request::query('search', '')}}">
            <span class="input-group-btn">
            <button class="btn btn-default btn-sm"><i class="fa fa-search"></i></button>
        </span>
        </div>
        {{--<a title="Import from active directory" href="#ImportModal" data-toggle="modal" class="btn btn-sm btn-primary"><i class="fa fa-download"></i></a>--}}
    </form>
@stop
@section('stylesheets')
    <style>

        .tiles-container {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: center;
        }

        .tile-container {
            display: flex;
            flex-direction: column;
            border: 1px solid #144e7f;
            border-radius: 10px;
            background: #144e7f;
            align-items: baseline;
            width: 200px;
            height: 252px;
        }

        .tile-image {
            border-radius: 9px;
        }

        .tile-body {
            display: flex;
            align-items: center;
            padding: 5px;
            align-self: center;
            height: 50px;
            color: #fff;
        }

        .tile-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 190px;
            height: 200px;
            background: white;
            margin: 5px;
            border-radius: 5px;
        }

        .tile {
            margin: 20px;
            text-decoration: none
        }

        .tile:hover {
            border-radius: 10px;
            box-shadow: 1px 5px 10px grey;
        }
    </style>
@endsection

@section('body')
    <section class="col-md-12 ">
        <div class=form-group></div>
        <h3 class=text-center>{{t('Select Business Unit') }}</h3>

        <div class="tiles-container">
            @foreach(\App\BusinessUnit::orderBy('name')->get() as $business_unit)
                <a href="{{route('business-unit.show', $business_unit)}}" class="tile">
                    <div class="tile-container" style="display: flex;align-items: center">
                        <div class="tile-icon" style="">
                            <img src="{{asset('images/logo.png')}}">
                        </div>
                        <div class="tile-body">
                            <p class="text-center">
                                {{$business_unit->name}}
                            </p>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

    </section>
@endsection