 <div class="h-auto bg-white rounded-xl m-5 shadow-md ">
        <div>
            <div class="flex bg-gray-300 p-5 ">
                <h3 class="panel-title"><i class="fa fa-user"></i> {{t('Users')}}</h3>
            </div>
            <div class="flex flex-col bg-white p-5 ">
                <a class="p-5 hover:bg-gray-200 hover:text-black hover:shadow-md rounded-xl hover:text-black"
                   href="{{route('admin.user.index')}}">{{t('Users')}}</a>
                <a class="p-5 hover:bg-gray-200 hover:text-black hover:shadow-md rounded-xl "
                   href="{{route('admin.group.index')}}">{{t('Groups')}}</a>
                <a class="p-5 hover:bg-gray-200 hover:text-black hover:shadow-md rounded-xl "
                   href="{{route('admin.group.user_groups')}}">{{t('User Groups')}}</a>
                <a class="p-5 hover:bg-gray-200 hover:text-black hover:shadow-md rounded-xl "
                   href="{{route('admin.role.index')}}">{{t('Roles')}}</a>
            </div>
        </div>


        <div>
            <div class="flex bg-gray-300 p-5 ">
                <h3 class="panel-title"><i class="fa fa-cubes"></i> {{t('Categories')}}</h3>
            </div>
            <div class="flex flex-col bg-white p-5 ">
                <a class="p-5 hover:bg-gray-200 hover:text-black hover:shadow-md rounded-xl "
                   href="{{route('admin.category.index')}}">{{t('Categories')}}</a>
                <a class="p-5 hover:bg-gray-200 hover:text-black hover:shadow-md rounded-xl "
                   href="{{route('admin.subcategory.index')}}">{{t('Subcategories')}}</a>
                <a class="p-5 hover:bg-gray-200 hover:text-black hover:shadow-md rounded-xl "
                   href="{{route('admin.item.index')}}">{{t('Items')}}</a>
                <a class="p-5 hover:bg-gray-200 hover:text-black hover:shadow-md rounded-xl "
                   href="{{route('admin.subItem.index')}}">{{t('SubItems')}}</a>
            </div>
        </div>

        <div>
            <div class="flex bg-gray-300 p-5 ">
                <h3 class="panel-title"><i class="fa fa-map-marker"></i> {{t('Locations')}}</h3>
            </div>
            <div class="flex flex-col bg-white p-5 ">
                <a class="p-5 hover:bg-gray-200 hover:text-black hover:shadow-md rounded-xl "
                   href="{{route('admin.region.index')}}">{{t('Regions')}}</a>
                <a class="p-5 hover:bg-gray-200 hover:text-black hover:shadow-md rounded-xl "
                   href="{{route('admin.city.index')}}">{{t('Cities')}}</a>
                <a class="p-5 hover:bg-gray-200 hover:text-black hover:shadow-md rounded-xl "
                   href="{{route('admin.location.index')}}">{{t('Location')}}</a>
            </div>
        </div>

        <div>
            <div class="flex bg-gray-300 p-5 ">
                <h3 class="panel-title"><i class="fa fa-building"></i> {{t('Business Units')}}</h3>
            </div>
            <div class="flex flex-col bg-white p-5 ">
                <a class="p-5 hover:bg-gray-200 hover:text-black hover:shadow-md rounded-xl "
                   href="{{route('admin.division.index')}}">{{t('Divisions')}}</a>
                <a class="p-5 hover:bg-gray-200 hover:text-black hover:shadow-md rounded-xl "
                   href="{{route('admin.business-unit.index')}}">{{t('Business Units')}}</a>
                <a class="p-5 hover:bg-gray-200 hover:text-black hover:shadow-md rounded-xl "
                   href="{{route('admin.branch.index')}}">{{t('Branches')}}</a>
                <a class="p-5 hover:bg-gray-200 hover:text-black hover:shadow-md rounded-xl "
                   href="{{route('admin.department.index')}}">{{t('Departments')}}</a>
            </div>
        </div>

        <div>
            <div class="flex bg-gray-300 p-5 ">
                <h3 class="panel-title"><i class="fa fa-building"></i> {{t('Configuration')}}</h3>
            </div>
            <div class="flex flex-col bg-white p-5 ">
                <a class="p-5 hover:bg-gray-200 hover:text-black hover:shadow-md rounded-xl "
                   href="{{route('admin.sla.index')}}">{{t('Service Level Agreement')}}</a>
                <a class="p-5 hover:bg-gray-200 hover:text-black hover:shadow-md rounded-xl "
                   href="{{route('admin.business-rule.index')}}">{{t('Business Rules')}}</a>
                <a class="p-5 hover:bg-gray-200 hover:text-black hover:shadow-md rounded-xl "
                   href="{{route('admin.priority.index')}}">{{t('Priority')}}</a>
                <a class="p-5 hover:bg-gray-200 hover:text-black hover:shadow-md rounded-xl "
                   href="{{route('admin.urgency.index')}}">{{t('Urgency')}}</a>
                <a class="p-5 hover:bg-gray-200 hover:text-black hover:shadow-md rounded-xl "
                   href="{{route('admin.impact.index')}}">{{t('Impact')}}</a>
                <a class="p-5 hover:bg-gray-200 hover:text-black hover:shadow-md rounded-xl "
                   href="{{route('admin.status.index')}}">{{t('Status')}}</a>
                <a class="p-5 hover:bg-gray-200 hover:text-black hover:shadow-md rounded-xl "
                   href="{{route('admin.status.index')}}">{{t('Availability')}}</a>
            </div>
        </div>


        <div>
            <div class="flex bg-gray-300 p-5 ">
                <h3 class="panel-title">{{t('Survey')}}</h3>

            </div>
            <div class="flex flex-col bg-white p-5 ">
                <a class="p-5 hover:bg-gray-200 hover:text-black hover:shadow-md rounded-xl "
                   href="{{route('admin.survey.index')}}">{{t('Surveys')}}</a>
                <a class="p-5 hover:bg-gray-200 hover:text-black hover:shadow-md rounded-xl "
                   href="{{route('admin.survey.create')}}">{{t('Define Survey')}}</a>
                <a class="p-5 hover:bg-gray-200 hover:text-black hover:shadow-md rounded-xl "
                   href="{{route('admin.sla.index')}}">{{t('Survey Log')}}</a>
            </div>
        </div>

     <div>
            <div class="flex bg-gray-300 p-5 ">
                <h3 class="panel-title">{{t('Letters')}}</h3>

            </div>
            <div class="flex flex-col bg-white p-5 ">
                <a class="p-5 hover:bg-gray-200 hover:text-black hover:shadow-md rounded-xl "
                   href="{{route('admin.survey.index')}}">{{t('Letter Group')}}</a>
            </div>
        </div>

    </div>
