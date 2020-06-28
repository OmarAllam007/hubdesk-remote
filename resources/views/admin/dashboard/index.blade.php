@extends('layouts.app')

@section('header')
    <h4>{{t('Admin')}}</h4>
@stop

@section('body')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-user"></i> {{t('Users')}}</h3>
                    </div>
                    <div class="panel-body">
                        <ul class="nav nav-pills">
                            <li><a href="{{route('admin.user.index')}}">{{t('Users')}}</a></li>
                            <li><a href="{{route('admin.group.index')}}">{{t('Groups')}}</a></li>
                            <li><a href="{{route('admin.group.user_groups')}}">{{t('User Groups')}}</a></li>
                            <li><a href="{{route('admin.role.index')}}">{{t('Roles')}}</a></li>
                        </ul>
                    </div>
                </div>
            </div>


            <div class="col-md-4">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-cubes"></i> {{t('Categories')}}</h3>
                    </div>
                    <div class="panel-body">
                        <ul class="nav nav-pills">
                            <li><a href="{{route('admin.category.index')}}">{{t('Categories')}}</a></li>
                            <li><a href="{{route('admin.subcategory.index')}}">{{t('Subcategories')}}</a></li>
                            <li><a href="{{route('admin.item.index')}}">{{t('Items')}}</a></li>
                            <li><a href="{{route('admin.subItem.index')}}">{{t('SubItems')}}</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-map-marker"></i> {{t('Locations')}}</h3>
                    </div>
                    <div class="panel-body">
                        <ul class="nav nav-pills">
                            <li><a href="{{route('admin.region.index')}}">{{t('Regions')}}</a></li>
                            <li><a href="{{route('admin.city.index')}}">{{t('Cities')}}</a></li>
                            <li><a href="{{route('admin.location.index')}}">{{t('Location')}}</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <hr/>
            </div>

            <div class="col-md-4">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-building"></i>  {{t('Business Units')}}</h3>
                    </div>
                    <div class="panel-body">
                        <ul class="nav nav-pills">
                            <li><a href="{{route('admin.division.index')}}">{{t('Divisions')}}</a></li>
                            <li><a href="{{route('admin.business-unit.index')}}">{{t('Business Units')}}</a></li>
                            <li><a href="{{route('admin.branch.index')}}">{{t('Branches')}}</a></li>
                            <li><a href="{{route('admin.department.index')}}">{{t('Departments')}}</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-cogs"></i> {{t('Configuration')}}</h3>
                    </div>
                    <div class="panel-body">
                        <ul class="nav nav-pills">
                            <li><a href="{{route('admin.sla.index')}}">{{t('Service Level Agreement')}}</a></li>
                            <li><a href="{{route('admin.business-rule.index')}}">{{t('Business Rules')}}</a></li>
                            <li><a href="{{route('admin.priority.index')}}">{{t('Priority')}}</a></li>
                            <li><a href="{{route('admin.urgency.index')}}">{{t('Urgency')}}</a></li>
                            <li><a href="{{route('admin.impact.index')}}">{{t('Impact')}}</a></li>
                            <li><a href="{{route('admin.status.index')}}">{{t('Status')}}</a></li>
                            <li><a href="{{route('admin.status.index')}}">{{t('Availability')}}</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">{{t('Survey')}}</h3>
                    </div>
                    <div class="panel-body">
                        <ul class="nav nav-pills">
                            <li><a href="{{route('admin.survey.index')}}">{{t('Surveys')}}</a></li>
                            <li><a href="{{route('admin.survey.create')}}">{{t('Define Survey')}}</a></li>
                            <li><a href="{{route('admin.sla.index')}}">{{t('Survey Log')}}</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection 