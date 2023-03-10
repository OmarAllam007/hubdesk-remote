@extends('layouts.app')

@section('header')
    <h4 class="pull-left">{{$role->name}}</h4>

    <div class="heading-actions pull-right">
        <a href="{{route('admin.role.edit', $role)}}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
        <a href="{{route('admin.role.index')}}" class="btn btn-sm btn-default"><i class="fa fa-chevron-left"></i></a>
    </div>
@endsection

@section('sidebar')
    @include('admin.partials._sidebar')
@stop

@section('body')
    <section class="col-sm-9">
    {{Form::open(['route' => ['admin.role.add-user', $role]])}}
    <div class="form-role">
        {{Form::label('user_id', 'User', ['class' => 'control-label'])}}
        <div class="row">
            <div class="col-md-9">
                {{Form::select('user_id', App\User::requesterList()->prepend('Select User', ''), null, ['class' => 'form-control select2'])}}
            </div>
            <div class="col-md-3">
                <button class="btn btn-sm btn-primary"><i class="fa fa-plus-circle"></i> Add User</button>
            </div>
        </div>
    </div>
    {{Form::close()}}

    <h4>Current Users</h4>
    @if ($role->users->count())
        <table class="listing-table">
            <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Business Unit</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($role->users as $user)
                <tr>
                    <td>{{$user->name}}</td>
                    <td>{{$user->email}}</td>
                    <td>{{$user->business_unit->name}}</td>
                    <td>
                        {{Form::open(['route' => ['admin.role.remove-user', $role, $user], 'method' => 'delete'])}}
                        <a href="{{route('admin.user.edit', $user)}}" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Edit</a>
                        <button type="submit" class="btn btn-xs btn-warning"><i class="fa fa-remove"></i> Remove</button>
                        {{Form::close()}}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <div class="alert alert-warning"><i class="fa fa-exclamation-circle"></i> No users found</div>
    @endif
    </section>
@endsection 