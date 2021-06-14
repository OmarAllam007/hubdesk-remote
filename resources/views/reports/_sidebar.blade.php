<div class="col-sm-3">
    @if(auth()->user()->isAdmin())

        <div class="form-group">
            <div class="btn-group">
                <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false">
                    {{t('Create')}} <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <li><a href="{{route('reports.create')}}">{{t('Standard Report')}}</a></li>

                        <li role="separator" class="divider"></li>
                        <li><a href="{{route('reports.query.create')}}">{{t('Query Report')}}</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="{{route('reports.scheduled_report.create')}}">{{t('Scheduled Report')}}</a></li>

                    <li role="separator" class="divider"></li>
                    <li><a href="{{route('reports.custom_report.create')}}">{{t('Custom Report')}}</a></li>

                </ul>
            </div>
        </div>
    @endif

    <div class="panel panel-info">

        <div class="panel-heading">
            <a href="{{route('folder.create')}}" class="btn btn-sm btn-success pull-right">
                <i class="fa fa-plus"></i> Create Folder
            </a>
            <h4>
                {{t('Folders')}}
            </h4>
        </div>
        @if($folders = auth()->user()->folders)
            <section class="list-group">
                @foreach($folders as $folder)
                    <a href="/reports?folder={{$folder->id}}"
                       class="list-group-item {{request('folder') == $folder->id? 'active' : ''}}">
                        <i class="fa fa-fw fa-folder{{request('folder') == $folder->id? '-open' : ''}}"></i> {{$folder->name}}
                    </a>
                @endforeach
                @if(auth()->user()->isAdmin())
                    <a href="{{route('reports.scheduled_report.index')}}"
                       class="list-group-item">
                        <i class="fa fa-fw fa-folder"></i> {{t('Scheduled Reports')}}
                    </a>
                @endif
            </section>
        @endif
    </div>
</div>