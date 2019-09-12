<div class="row"  id="reports">
    <div class="col-md-12">
        {{csrf_field()}}
        <scheduled-report :reports="{{$reports}}" :users="{{$users}}" >

        </scheduled-report>
        <div class="form-group">
            <button class="btn btn-success"><i class="fa fa-check-circle"></i> {{t('Submit')}}</button>
        </div>
    </div>

</div>
