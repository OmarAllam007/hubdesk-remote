<div class="modal fade" id="showRemarks" style="padding-top: 100px">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">{{t('Remarks')}}</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <p id="remarks"></p>
                    </div>
                </div>

            </div>

{{--            <div class="modal-footer">--}}
{{--                <button type="submit" class="btn btn-primary"><i class="fa fa-mail-forward"></i> {{t('Close')}}</button>--}}
{{--            </div>--}}
        </div>
    </div>
</div>
{{Form::close()}}