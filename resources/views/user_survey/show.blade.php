@extends('layouts.app')

@section('header')
    <h4 class="pull-left">Survey - #{{$user_survey->id}} submitted By {{$user_survey->ticket->requester->name}}</h4>


@endsection


@section('body')

    @php
        $total = 0;
    @endphp
    <section class="col-sm-9">.
        <div class="pull-right">
            <a href="{{route('ticket.show', $user_survey->ticket)}}" class="btn btn-lg btn-default"><i
                        class="fa fa-eye"></i> Display Ticket</a>
        </div>
        <h4>{{t('Questions')}}</h4>
        @if ($user_survey->survey->questions->count())
            <table class="listing-table">
                <thead>
                <tr>
                    <th>{{t('Description')}}</th>
                    <th>{{t('Answer')}}</th>
                    <th>{{t('Degree')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($user_survey->survey->questions as $question)
                    @php
                        $degree = \App\UserSurveyAnswer::where('user_survey_id',$user_survey->id)
                                        ->where('question_id',$question->id)->first()->answer->degree ?? 0;
                        $total +=  $degree;
                    @endphp

                    <tr>
                        <td>{{$question->description}}</td>
                        <td>
                            {{\App\UserSurveyAnswer::where('user_survey_id',$user_survey->id)
                            ->where('question_id',$question->id)->first()->answer->description ?? ''}}
                        </td>
                        <td>
                            {{$degree}}
                        </td>
                    </tr>
                @endforeach
                </tbody>

                <tfooter>
                    <tr>
                        <td colspan="2">{{t('Total')}}</td>
                        <td> {{$total}} </td>
                    </tr>
                </tfooter>
            </table>


            @if ($user_survey->comment)
                <p class="bg-info" style="padding: 20px;border-radius: 10px;font-weight: bolder">
                    Comment: {!! nl2br(e($user_survey->comment)) !!} </p>
            @endif
        @else
            <div class="alert alert-warning">
                <i class="fa fa-exclamation-circle"></i>
                No Questions found
            </div>
        @endif

    </section>
@endsection