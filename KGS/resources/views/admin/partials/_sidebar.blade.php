<div class="col-sm-3">
    <section class="panel panel-primary" id="#admin-sidebar">
        <div class="panel-heading">
            <h5 class="panel-title">{{t('Control Panel')}}</h5>
        </div>

        <nav class="list-group">
            <p class="list-group-item text-center info"><strong><i class="fa fa-fw fa-cogs"></i> {{t('Settings')}}</strong></p>
            <a href="{{route('kgs.admin.category.index')}}" class="list-group-item"><i class="fa fa-fw fa-cubes"></i> {{t('Categories')}}</a>
            <a href="{{route('kgs.admin.subcategory.index')}}" class="list-group-item"><i class="fa fa-fw fa-bars"></i> {{t('Subcategories')}}</a>
            <a href="{{route('kgs.admin.item.index')}}" class="list-group-item">
                <i class="fa fa-fw fa-server "></i> {{t('Items')}}</a>
            <a href="{{route('kgs.admin.business_unit.index')}}" class="list-group-item">
                <i class="fa fa-fw fa-building "></i> {{t('Business Units')}}</a>
{{--            <a href="{{route('admin.status.index')}}" class="list-group-item"><i class="fa fa-bars"></i> Subcategories</a>--}}
{{--            <a href="{{route('admin.status.index')}}" class="list-group-item"><i class="fa fa-gg"></i> Items</a>--}}
        </nav>

    </section>
</div>