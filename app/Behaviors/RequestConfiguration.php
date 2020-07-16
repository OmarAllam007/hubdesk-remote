<?php
namespace App\Behaviors;
use App\BusinessRule;
use App\User;

trait RequestConfiguration {
    function getRequestCcEmails(){
        $cc = collect();

        $rules = BusinessRule::with('criterions')->with('rules')->get();
        foreach ($rules as $rule) {
            if ($this->match($rule)) {
                foreach ($rule->rules as $action) {
                    if ($action['field'] == 'cc') {
                        $cc->push(User::find($action['value'])->email);
                    }
                }
            }
        }
        return $cc->filter();
    }


    function getSla($category, $subcategory = null, $item = null, $subItem = null)
    {
        $this->category_id = $category->id;

        if ($subcategory) {
            $this->subcategory_id = $subcategory->id;
        }

        if ($item) {
            $this->item_id = $item->id;
        }

        $sla = new \App\Jobs\ApplySLA($this);
        $data = $sla->fetchSLA();

        return $data;
    }


    function getSubjectLabelAttribute()
    {
        $label = $this->category->name;
        if ($this->subcategory) {
            $label .= ' >' . $this->subcategory->name;
        }
        if ($this->item) {
            $label .= ' >' . $this->item->name;
        }
        return $label;
    }

    function isClosed()
    {
        return $this->status_id == 8;
    }

    function taskJson()
    {
        return [
            'id' => $this->id,
            'subject' => $this->subject ?? '',
            'description' => $this->description ?? '',
            'status' => $this->status->name ?? '',
            'requester' => $this->requester->name ?? '',
            'created_at' => $this->created_at->format('d/m/Y H:i') ?? '',
            'technician' => $this->technician->name ?? '',
            'technician_id' => $this->technician->id ?? '',
            'request_id' => $this->request_id ?? '',
            'can_edit' => can('task_edit', $this),
            'can_show' => can('task_show', $this),
            'can_delete' => can('task_destroy', $this)
        ];
    }
}