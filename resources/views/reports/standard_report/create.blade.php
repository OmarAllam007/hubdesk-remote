@extends('layouts.app')

@section('header')
    <div class="display-flex">
        <h2 class="flex">Create Report</h2>

        <a href="{{route('reports.index')}}" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i> Back</a>
    </div>
@endsection

@section('body')
    <form action="{{route('reports.store')}}" method="post" class="col-sm-9">
        {{csrf_field()}}

        @include('reports.standard_report._form')
    </form>
@endsection

@section('javascript')
    <script>
        window.jQuery(function($) {

            $('.multi-select').on('click', '.select-all', e => {
                e.preventDefault();
                $(e.target).closest('.multi-select').find('input').prop('checked', 'checked');
            }).on('click', '.remove-all', e => {
                e.preventDefault();
                $(e.target).closest('.multi-select').find('input').prop('checked', false);
            }).on('keyup', '.search', e => {
                const $self = $(e.target);
                const term = $self.val().toLowerCase();
                if (term) {
                    $self.closest('.multi-select-container').find('.checkbox-label').each((idx, item) => {
                        const val = $(item).text().toLowerCase();

                        if (val.indexOf(term) >= 0) {
                            $(item).closest('li').show();
                        } else {
                            $(item).closest('li').hide();
                        }
                    });
                } else {
                    $self.closest('.multi-select-container').find('.checkbox').show();
                }

            });
        });
    </script>
@endsection