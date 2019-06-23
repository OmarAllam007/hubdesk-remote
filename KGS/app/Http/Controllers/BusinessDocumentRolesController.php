<?php
/**
 * Created by PhpStorm.
 * User: omarkhaled
 * Date: 2019-03-10
 * Time: 10:55
 */

namespace KGS\Http\Controllers;


use App\Attachment;
use App\BusinessUnit;
use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Item;
use App\Subcategory;
use App\Task;
use App\Ticket;
use KGS\Requirement;
use Predis\Response\Status;

class BusinessDocumentRolesController extends Controller
{
    function show(BusinessUnit $business_unit)
    {
        return view('kgs::business-documents.roles.show', compact('business_unit'));
    }

    function update(BusinessUnit $business_unit, Request $request)
    {
        $business_unit->document_roles()->sync($request->users);
        return redirect()->route('kgs.document.select_category',compact('business_unit'));
    }
}