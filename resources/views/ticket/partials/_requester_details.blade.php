<div class="panel panel-default panel-design">
    <div class="panel-heading">
        <h4 class="panel-title"><i class="fa fa-user"></i> {{t('Requester Details')}}</h4>
    </div>
    <table class="table table-striped table-condensed details-tbl">
        <tr>
            <th class="col-sm-3">{{t('Name')}}</th>
            <td class="col-sm-3">{{$ticket->requester->name}}</td>
            <th class="col-sm-3">{{t('Business Unit')}}</th>
            <td class="col-sm-3">{{$ticket->requester->business_unit->name ?? 'Not Assigned'}}</td>
        </tr>
        <tr>
            <th>{{t('Department')}}</th>
            <td>{{$ticket->requester->department->name ?? 'Not Assigned'}}</td>
            <th>{{t('Job Title')}}</th>
            <td>{{$ticket->requester->job ?? 'Not Assigned'}}</td>
        </tr>
        <tr>
            <th>{{t('Email')}}</th>
            <td>{{$ticket->requester->email ?? 'Not Assigned'}}</td>
            <th>{{t('Employee ID')}}</th>
            <td>{{$ticket->requester->employee_id ?? 'Not Assigned'}}</td>
        </tr>
        <tr>
            <th>{{t('Phone')}}</th>
            <td>{{$ticket->requester->phone ?? 'Not Assigned'}}</td>
            <th>{{t('Mobile')}}</th>
            <td>{{$ticket->requester->mobile ?? 'Not Assigned'}}</td>
        </tr>


    </table>
</div>