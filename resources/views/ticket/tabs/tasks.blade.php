<tasks :ticket_id="{{$ticket->id}}" inline-template>
    <div>
        <div>

            @can('task_create',$ticket)
                <button data-toggle="modal" data-target="#TaskForm" type="button" @click="resetTask"
                        class="btn btn-sm btn-success pull-right" title="{{t('Create Task')}}">
                    <i class="fa fa-plus"></i> {{t('Create Task')}}
                </button>
            @endcan
        </div>
        <div class="clearfix"></div>
        <br>
        <table class="listing-table" v-if="tasks[0]">
            <thead class="table-design">
            <tr>
                <th>{{t('ID')}}</th>
                <th>{{t('Subject')}}</th>
                <th>{{t('Category')}}</th>
                <th>{{t('Status')}}</th>
                <th>{{t('Created At')}}</th>
                <th>{{t('Created By')}}</th>
                <th>{{t('Assigned To')}}</th>
{{--                @can('task_show',$ticket->tasks->first())--}}
                    <th>{{t('Actions')}}</th>
{{--                @endif--}}
            </tr>
            </thead>
            <tbody>
            <tr v-for="task in tasks">
                <td class="col-md-1">
                    <a v-bind:href="'/ticket/'+ task.id" v-if="task.can_show">@{{ task.id }}</a>
                    <p v-else>@{{ task.id }}</p>
                </td>
                <td > @{{ task.subject }}</td>
                <td > @{{ task.category }}</td>
                <td > @{{ task.status }}</td>
                <td > @{{ task.created_at }}</td>
                <td > @{{ task.requester }}</td>
                <td > @{{ task.technician }}</td>
                <td >
                    <a v-if="task.can_show || task.can_show" class="btn btn-rounded  btn-info" v-bind:href="'/ticket/'+ task.id" v-if="task.can_show">
                        <i class="fa fa-eye"></i>
                        {{t('Show')}}
                    </a>
                    {{--                    @can('modify',$ticket)--}}
                    <a  v-if="task.can_edit" class="btn btn-rounded btn-warning"
                       :href="'/ticket/tasks/edit/'+ task.id" >
                        <i class="fa fa-edit"></i>
                        {{t('Edit')}}
                    </a>
                    {{--                    @endcan--}}
                    <button v-if="task.can_show || task.can_show" class="btn btn-rounded  btn-danger" v-on:click="deleteTask(task.id)" v-if="task.can_delete">{{t('Delete')}}</button>
                </td>
            </tr>

            </tbody>
        </table>
        <div class="alert alert-info" v-else="tasks[0]"><i class="fa fa-exclamation-circle"></i>
            <strong>{{t('No Tasks found')}}</strong>
        </div>
        @include('ticket._create_task')
    </div>

</tasks>
