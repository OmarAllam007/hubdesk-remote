<?php

namespace KGS\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use KGS\BusinessDocumentsFolder;
use KGS\Document;

class DocumentController extends Controller
{
    function index(BusinessDocumentsFolder $folder)
    {
        $documents = $folder->documents()->paginate(20);
        return view('kgs::business-documents.documents.index', compact('documents', 'folder'));
    }

    function create(BusinessDocumentsFolder $folder)
    {
        return view('kgs::business-documents.documents.create', compact('folder'));
    }

    function edit(BusinessDocumentsFolder $folder, Document $document)
    {
        return view('kgs::business-documents.documents.edit', compact('folder', 'document'));
    }

    function store(BusinessDocumentsFolder $folder, Request $request)
    {
        $this->validate($request,['name'=>'required','end_date'=>'required','document'=>'required','folder_id'=>'required']);

        if ($request->hasFile('document')) {
            $request['document_path'] = $this->uploadDocument($folder, $request);
        }
        Document::create([
            'folder_id' => $folder->id,
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'path' => $request['document_path'] ?? '',
            'last_updated_by' => \Auth::id(),
        ]);

        return redirect()->route('kgs.document.index', compact('folder'));
    }

    function update(BusinessDocumentsFolder $folder, Document $document, Request $request)
    {
        $this->validate($request,['name'=>'required','end_date'=>'required','folder_id'=>'required']);


        if ($request->hasFile('document')) {
            $request['document_path'] = $this->uploadDocument($folder, $request);
        }

        $document->update([
            'folder_id' =>$request->folder_id,
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'path' => $request['document_path'] ? $request['document_path'] : $document->path,
//            'last_updated_by' => \Auth::id(),
        ]);
        return redirect()->route('kgs.document.index', compact('folder'));
    }

    function destroy(BusinessDocumentsFolder $folder, Document $document)
    {
        $document->delete();
        return redirect()->route('kgs.document.index', compact('folder'));
    }

    private function uploadDocument(BusinessDocumentsFolder $business_folder, Request $request)
    {
        $file = \request('document');
        if ($file) {
            $filename = $file->getClientOriginalName();

            $folder = storage_path('app/public/attachments/business-units/' . $business_folder->id . '/');
            if (!is_dir($folder)) {
                mkdir($folder, 0775, true);
            }

            $path = $folder . $filename;
            if (is_file($path)) {
                $filename = uniqid() . '_' . $filename;
                $path = $folder . $filename;
            }

            $file->move($folder, $filename);

            $file->path = '/attachments/business-units/' . $business_folder->id . '/' . $filename;
            return $file->path;
        }
    }


    function downloadAttachment(Document $document)
    {
        $file = public_path('storage') . $document->path;
        return response()->download($file);
    }

}
