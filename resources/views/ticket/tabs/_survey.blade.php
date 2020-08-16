<table class="listing-table">
    <thead class="table-design">
    <tr>
        <th>Question</th>
        <th>Answer</th>
        <th>Degree</th>
    </tr>
    </thead>
    <tbody>
    @foreach($ticket->user_survey->survey_answers as $answer)
        <tr>
            <td>{{$answer->answer->question->description}}</td>
            <td>{{$answer->answer->description}}</td>
            <td>{{number_format($answer->answer->degree,2)}}</td>
        </tr>
    @endforeach
    <tr class="bg-success">
        <td colspan="2">Total</td>
        <td><strong>
                {{number_format($ticket->user_survey->total_score,2) ?? ''}}
            </strong></td>
    </tr>
    </tbody>


</table>

