@extends('layouts.app')

@section('header')
<h4 class="pull-left">{{t('Subcategories')}}</h4>
@stop



@section('body')
<section class="col-sm-9">
    @if ($subcategories->total())
    <table class="listing-table">

        <tbody>
            @foreach($subcategories as $subcategory)
            <tr>
                <td class="col-md-4">{{t($subcategory->category->name ?? '')}}</td>
                <td class="col-md-3">

                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @include('partials._pagination', ['items' => $subcategories])
    @else
    <div class="alert alert-info"><i class="fa fa-exclamation-circle"></i> <strong>{{t('No subcategories found')}}</strong></div>
    @endif
</section>
@stop
