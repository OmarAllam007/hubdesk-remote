<?php
/**
 * Created by PhpStorm.
 * User: omarkhaled
 * Date: 2019-03-10
 * Time: 10:55
 */

namespace KGS\Http\Controllers;


use App\BusinessUnit;
use App\Category;
use App\Item;
use App\Subcategory;

class BusinessDocumentController
{
    function index()
    {
        return view('kgs::business-documents.wizard.index');
    }

    function selectCategory(BusinessUnit $business_unit)
    {
        return view('kgs::business-documents.wizard.select_category', compact('business_unit'));
    }

    function selectSubcategory(BusinessUnit $business_unit, Category $category)
    {
        return view('kgs::business-documents.wizard.select_subcategory', compact('business_unit', 'category'));
    }

    function selectItem(BusinessUnit $business_unit, Category $category, Subcategory $subcategory)
    {
        if($subcategory->items->count()){
            return view('kgs::business-documents.wizard.select_item', compact('business_unit', 'category','subcategory'));
        }

        $requirements = $category->requirements->merge($subcategory->requirements);
        if($requirements->count()){
            return view('kgs::business-documents.wizard.requirements',compact('requirements','business_unit','category','subcategory'));
        }
    }

    function checkForRequirements(BusinessUnit $business_unit, Category $category, Subcategory $subcategory,Item $item){

        $requirements = $category->requirements->merge($subcategory->requirements);
        $requirements = $requirements->merge($item->requirements);

        if($requirements->count()){
            return view('kgs::business-documents.wizard.requirements',compact('business_unit','category','subcategory','item','requirements'));
        }

    }

}