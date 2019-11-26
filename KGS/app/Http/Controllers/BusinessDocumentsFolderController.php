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
use App\Item;
use App\Subcategory;
use App\Task;
use App\Ticket;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use KGS\BusinessDocumentsFolder;
use KGS\DocumentNotification;
use KGS\Requirement;
use Predis\Response\Status;

class BusinessDocumentsFolderController extends Controller
{
    function index(BusinessUnit $business_unit)
    {
        $document_folders = BusinessDocumentsFolder::where('business_unit_id', $business_unit->id)->paginate(15);
        return view('kgs::business-documents.document_folder.index', compact('document_folders', 'business_unit'));
    }

    function create(BusinessUnit $business_unit)
    {
        return view('kgs::business-documents.document_folder.create', compact('business_unit'));
    }

    function store(BusinessUnit $business_unit, Request $request)
    {
        BusinessDocumentsFolder::create([
            'business_unit_id' => $business_unit->id,
            'name' => $request->name,
            'creator_id' => auth()->user()->id,
        ]);

        return redirect()->route('kgs.business_documents_folder.index', compact('business_unit'));
    }

    function edit(BusinessDocumentsFolder $folder)
    {
        $business_unit = $folder->business_unit;
        return view('kgs::business-documents.document_folder.edit', compact('business_unit', 'folder'));

    }

    function update(BusinessDocumentsFolder $folder,Request $request)
    {

        $business_unit = $folder->business_unit;

        $folder->update($request->all());

        return redirect()->route('kgs.business_documents_folder.index', compact('business_unit'));
    }

    function destroy(BusinessDocumentsFolder $folder)
    {
        $folder->delete();

        return redirect()->back();
    }
}