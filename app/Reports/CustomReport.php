<?php

namespace App\Reports;

use App\Report;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class CustomReport extends Model
{
    use SoftDeletes;

    protected $fillable = ['title', 'folder_id', 'user_id', 'parameters'];

    protected $casts = ['parameters' => 'array'];


    function user()
    {
        return $this->belongsTo(User::class);
    }

    function getTypeAttribute(){
        return Report::$CUSTOM_REPORT;
    }

//
//    protected $columns;
//
//    function run()
//    {
//        $query = $this->parameters['query'] ?? $this->report['query'];
//        $params = $this->parameters['parameters'] ?? $this->report['parameters'];
//
//        if(count($filters = request()->get('filters'))){
//            $filters  = $this->getBindingsParams($params,$filters);
//            $this->data = collect(\DB::select(\DB::raw($query),$filters));
//            $this->columns = collect($this->data->first())->keys() ;
//        }else{
//            $this->data = collect();
//            $this->columns = collect();
//        }
//
//
//    }
//
//    function html()
//    {
//        $page = LengthAwarePaginator::resolveCurrentPage();
//        $items = $this->data->forPage($page, $this->perPage);
//        $pager = new LengthAwarePaginator($items, $this->data->count(), $this->perPage);
//
//        $pager->setPath('');
//
//        return view('reports.query_report', ['report'=>$this->report , 'items' => $pager,'columns'=> $this->columns]);
//    }
//
//    function excel()
//    {
//
//    }
//
//    function pdf()
//    {
//
//    }
//
//    function csv()
//    {
//
//    }
//
//    private function getBindingsParams($params,$filters)
//    {
//        foreach ($filters as $key=>$filter){
//            if($params[$key]["type"] == "datetime"){
//                $filters[$key] = Carbon::parse($filter,'AST')->toDateTimeString();
//            }
//        }
//        return $filters;
//    }


}