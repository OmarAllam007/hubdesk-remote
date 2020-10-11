<div>
    <section class="table-container">
        <table class="listing-table table-bordered">
            <thead>
            <tr>
                <th class="col-md-10">{{t('Questions')}}</th>
                <th>
                    <button class="btn btn-sm btn-primary pull-right" wire:click.prevent="addNewQuestion" type="button"><i
                                class="fa fa-plus-circle"></i></button>
                </th>
            </tr>
            </thead>
            <tbody>
            @foreach($questions as $question)
{{--                <tr>--}}
{{--                    <td class="col-md-10">--}}
{{--                        <input type="text" class="form-control" :name="`questions[${index}][description]`"--}}
{{--                               placeholder="Description"--}}
{{--                               v-model="question.description">--}}
{{--                    </td>--}}

{{--                    <td class="col-md-2">--}}
{{--                        <button class="btn btn-sm btn-warning pull-right" type="button" @click="remove()"><i--}}
{{--                                    class="fa fa-remove"></i></button>--}}
{{--                    </td>--}}
{{--                </tr>--}}
            </tbody>
            @endforeach
        </table>
    </section>
</div>
