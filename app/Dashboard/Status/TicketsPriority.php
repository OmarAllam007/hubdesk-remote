<?php


namespace App\Dashboard\Status;


use App\Category;
use Illuminate\Database\Eloquent\Collection;

class TicketsPriority
{
    private $rowData;
    private $from;
    private $to;
    /**
     * @var \Illuminate\Support\Collection
     */
    private $categories;

    public function __construct($rowData, $from, $to, $categories)
    {
        $this->rowData = $rowData;
        $this->from = $from;
        $this->to = $to;
        $this->categories = $categories;
    }

    function process()
    {
        $data = $this->ticketsPriorityFillData();

        $footerData = $this->ticketsPriorityFillFooter($data);
        $footerRow = $footerData['footerRow'];
        $chartData = $footerData['chartData'];

        $footerRow->put('Total', $footerRow->values()->sum());

        $ticketPriorityData = collect();
        $ticketPriorityData->put('priorities', $data);
        $ticketPriorityData->put('chartData', $chartData);
        $ticketPriorityData->put('header', $this->categories);
        $ticketPriorityData->put('footer', $footerRow);

        return $ticketPriorityData;
    }

    private function ticketsPriorityFillData()
    {

        return $this->rowData->whereBetween('created_at', [$this->from, $this->to])
            ->groupBy(function ($ticket) {
                return $ticket->Priority ? $ticket->Priority : 'Not Assigned';
            })->sortKeys()->map(function (Collection $items, $key) {
                $itemsArr = collect();

                foreach ($this->categories as $category) {
                    $itemsArr->put($category, 0);
                }

                $items->each(function ($item) use ($itemsArr) {
                    $itemsArr->put($item->Category, $itemsArr->get($item->Category) + 1);
                });

                return [
                    'Total' => $items->count(),
                    'items' => $itemsArr
                ];
            });
    }

    private function ticketsPriorityFillFooter($data)
    {
        $footerRow = collect();
        $chartData = collect();
        foreach ($this->categories as $category) {
            $footerRow->put($category, 0);
        }

        $data->each(function ($priority, $key) use ($chartData, $footerRow) {
            $chartData->put($key, $priority['Total']);

            $priority['items']->each(function ($item, $itemKey) use ($footerRow) {
                $footerRow->put($itemKey, $footerRow->get($itemKey) + $item);
            });

        });
        $data = collect(['footerRow' => $footerRow, 'chartData' => $chartData]);

        return $data;
    }
}