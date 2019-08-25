<div class="col-sm-3">

    <div class="form-group">
        <div class="btn-group">
            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                {{t('Create')}} <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li><a href="{{route('reports.create')}}">{{t('Standard Report')}}</a></li>
                <li role="separator" class="divider"></li>
                <li><a href="#">{{t('Query Report')}}</a></li>
                <li role="separator" class="divider"></li>
                <li><a href="#">{{t('Custom Report')}}</a></li>
                <li role="separator" class="divider"></li>
                <li><a href="#">{{t('Scheduled Report')}}</a></li>
            </ul>
        </div>
    </div>

    <div class="panel panel-info">
        <div class="panel-heading">
            <h4 class="panel-title">{{t('Folders')}}</h4>
        </div>

        <section class="list-group">
            @foreach($folders as $folder)
                <a href="/reports?folder={{$folder->id}}" class="list-group-item {{request('folder') == $folder->id? 'active' : ''}}">
                    <i class="fa fa-fw fa-folder{{request('folder') == $folder->id? '-open' : ''}}"></i> {{$folder->name}}
                </a>
            @endforeach
        </section>
    </div>
</div>