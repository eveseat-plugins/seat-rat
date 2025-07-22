<?php
namespace RecursiveTree\Seat\RattingMonitor\Http\Datatables;

use Yajra\DataTables\Services\DataTable;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

abstract class RattingDataTable extends DataTable
{
    abstract public function query($system,$start,$end);

    public function getColumns()
    {
        return [
            ['data' => 'character_name', 'title' => 'Character'],
            ['data' => 'ratted_money', 'title' => 'Ratted Money'],
        ];
    }

    public function html()
    {
        $days = intval(request()->query("days")) ?: 30;
        $system = intval(request()->query("system")) ?: 30000142;

        return $this->builder()
            ->postAjax()
            ->parameters([
                'dom'          => '<"row"<"col-sm-12 col-md-4"l><"col-sm-12 col-md-4 text-center"B>><"row"<"col-sm-12"tr>><"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                'buttons' => ['csv', 'excel'],
                'drawCallback' => "function(d) { d.system = $system; d.days = $days; }",
            ])
            ->columns($this->getColumns())
            ->orderBy(1, 'desc');
    }

    public function ajax(): JsonResponse
    {
        if(request()->query("timeType")=="month"){
            $start = carbon(request()->query("month"))->startOfMonth();
            $end = carbon(request()->query("month"))->endOfMonth();
        } else {
            $start = now()->subDays(intval(request()->query("days")) ?: 30);
            $end = now();
        }
        $system = intval(request()->query("system")) ?: 30000142;

        $ajax = datatables()
            ->of($this->query($system,$start,$end))
            ->editColumn('character_name', function ($row) {
                return view("rattingmonitor::charactername",[
                    "character_id"=>$row->character_id,
                    "name"=>$row->character_name
                ])->render();
            })
            ->editColumn('ratted_money', function ($row) {
                return number($row->ratted_money) . " ISK";
            })
            ->rawColumns(["character_name"]);

        return $ajax->make(true);
    }
}