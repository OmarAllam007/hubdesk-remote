<?php


namespace App\Dashboard\Status;


use Illuminate\Database\Eloquent\Collection;

class TicketPriorityVsCategory
{
    private $data;
    /**
     * @var \Illuminate\Support\Collection
     */
    private $categories;
    private $from;
    private $to;
    private $date;
    private $statuses;

    public function __construct($data, $from, $to, $categories, $date, $statuses)
    {
        $this->data = $data;
        $this->from = $from;
        $this->to = $to;
        $this->categories = $categories;
        $this->date = $date;
        $this->statuses = $statuses;

        $this->process();
    }


    function process()
    {
        $data = $this->fillData();
        $footerData = $this->fillFooter($data);
        $footerRow = $footerData['footerRow'];
        $chartData = $footerData['chartData'];

        $footerRow->put('Total', $footerRow->values()->sum());

        $ticketStatusData = collect();
        $ticketStatusData->put('priorities', $data);
        $ticketStatusData->put('chartData', $chartData);
        $ticketStatusData->put('header', $this->categories);
        $ticketStatusData->put('footer', $footerRow);

        return $ticketStatusData;
    }

    function fillData()
    {

        return $this->data->whereNotNull($this->date)
            ->whereBetween($this->date, [$this->from, $this->to])->whereIn('StatusID', $this->statuses)
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

    private function fillFooter($data)
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