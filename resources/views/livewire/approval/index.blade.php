
<div class="col-md-10 " >
    {{-- A good traveler has no fixed plans and is not intent upon arriving. --}}
    @can('submit_approval',$ticket)
        <div class="form-group">
            <button wire:click.prevent="addStages" class="btn btn-primary btn-sm"><i
                        class="fa fa-plus"></i></button>
        </div>

        @if($approvalLevels->count())
            @foreach($approvalLevels as $key=>$approvalLevel)
                <livewire:approval.approval-item :level="$approvalLevel" :users="$users"  :key="$key" :index="$key" :showStage="$approvalLevels->count() && $key > 1">

{{--                @livewire('approval.approval-item')--}}
            @endforeach
        @endif
        <div class="form-group">
            <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> {{t('Send')}}</button>
        </div>
    @endcan

</div>

