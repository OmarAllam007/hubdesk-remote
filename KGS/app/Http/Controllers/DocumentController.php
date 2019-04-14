<?php

namespace KGS\Http\Controllers;


use App\BusinessUnit;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use KGS\Document;

class DocumentController extends Controller
{
    function index(BusinessUnit $business_unit)
    {
        $documents = $business_unit->documents()->paginate(20);
        return view('kgs::business-documents.documents.index', compact('documents', 'business_unit'));
    }

    function create(BusinessUnit $business_unit)
    {
        return view('kgs::business-documents.documents.create', compact('business_unit'));
    }

    function edit(BusinessUnit $business_unit, Document $document)
    {
        return view('kgs::business-documents.documents.edit', compact('business_unit', 'document'));
    }

    function store(BusinessUnit $business_unit, Request $request)
    {
        $this->validate($request,['name'=>'required','end_date'=>'required','document'=>'required']);

        if ($request->hasFile('document')) {
            $request['document_path'] = $this->uploadDocument($business_unit, $request);
        }
        Document::create([
            'business_unit_id' => $business_unit->id,
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'path' => $request['document_path'] ?? '',
            'last_updated_by' => \Auth::id(),
        ]);

        return redirect()->route('kgs.document.index', compact('business_unit'));
    }

    function update(BusinessUnit $business_unit, Document $document, Request $request)
    {
        $this->validate($request,['name'=>'required','end_date'=>'required']);


        if ($request->hasFile('document')) {
            $request['document_path'] = $this->uploadDocument($business_unit, $request);
        }
        $document->update([
            'business_unit_id' => $business_unit->id,
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'path' => $request['document_path'] ? $request['document_path'] : $document->path,
            'last_updated_by' => \Auth::id(),
        ]);
        return redirect()->route('kgs.document.index', compact('business_unit'));
    }

    function destroy(BusinessUnit $business_unit, Document $document)
    {
        $document->delete();
        return redirect()->route('kgs.document.index', compact('business_unit'));
    }

    private function uploadDocument(BusinessUnit $business_unit, Request $request)
    {
        $file = \request('document');
        if ($file) {
            $filename = $file->getClientOriginalName();

            $folder = storage_path('app/public/attachments/business-units/' . $business_unit->id . '/');
            if (!is_dir($folder)) {
                mkdir($folder, 0775, true);
            }

            $path = $folder . $filename;
            if (is_file($path)) {
                $filename = uniqid() . '_' . $filename;
                $path = $folder . $filename;
            }

            $file->move($folder, $filename);

            $file->path = '/attachments/business-units/' . $business_unit->id . '/' . $filename;
            return $file->path;
        }
    }

}
