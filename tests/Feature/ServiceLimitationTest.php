<?php

namespace Tests\Feature;

use App\BusinessUnit;
use App\Category;
use App\Item;
use App\Subcategory;
use App\SubItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ServiceLimitationTest extends TestCase
{
    /** @test */
    public function a_user_notified_if_exceed_service_limitation()
    {
        /** @var BusinessUnit $requester_bu */
        $requester_bu = auth()->user()->business_unit;
        $business_unit = BusinessUnit::find(11);
        $category = Category::find(91);
        $subcategory = Subcategory::find(290);
        $item = Item::find(113);
        $subItem = SubItem::find(12);

        $response = $this->get("ticket/create-ticket/business-unit/{$business_unit->id}/category/{$category->id}/subcategory/{$subcategory->id}/item/{$item->id}/subItem/{$subItem->id}");
        $this->assertContains('The number of allowed requests per month is exceeded',$response->content());
   }
}
