<?php

namespace App\Http\Controllers;

use App\Models\Wedding;
use App\Models\WeddingDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class WeddingDocumentController extends Controller
{
    /**
     * Display wedding documents and photos.
     */
    public function index(Request $request): View
    {
        $userId = auth()->id();

        $wedding = Wedding::where(
            'user_id',
            $userId
        )->first();

        if (!$wedding) {

            return view('wedding.documents', [
                'wedding' => null,
                'documents' => collect(),
                'categories' => collect(),
                'totalFiles' => 0,
                'totalPhotos' => 0,
                'totalDocuments' => 0,
            ]);
        }

        $query = $wedding->documents()
            ->latest();

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->input('search');

            $query->where(function ($query) use ($search) {

                $query
                    ->where('file_name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");

            });
        }

        /*
        |--------------------------------------------------------------------------
        | Category
        |--------------------------------------------------------------------------
        */

        if ($request->filled('category')) {

            $query->where(
                'category',
                $request->input('category')
            );
        }

        $documents = $query->get();

        /*
        |--------------------------------------------------------------------------
        | All wedding files for statistics
        |--------------------------------------------------------------------------
        */

        $allDocuments = $wedding->documents()->get();

        $totalFiles = $allDocuments->count();

        $totalPhotos = $allDocuments
            ->filter(
                fn ($document) => $document->is_image
            )
            ->count();

        $totalDocuments = $totalFiles - $totalPhotos;

        /*
        |--------------------------------------------------------------------------
        | Categories
        |--------------------------------------------------------------------------
        */

        $categories = $wedding->documents()
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view(
            'wedding.documents',
            compact(
                'wedding',
                'documents',
                'categories',
                'totalFiles',
                'totalPhotos',
                'totalDocuments'
            )
        );
    }


    /**
     * Store a new wedding document/photo.
     */
    public function store(
        Request $request
    ): RedirectResponse {

        $userId = auth()->id();

        $wedding = Wedding::where(
            'user_id',
            $userId
        )->firstOrFail();

        $validated = $request->validate([
            'file' => [
                'required',
                'file',
                'max:20480',
                'mimes:jpg,jpeg,png,webp,gif,pdf,doc,docx,xls,xlsx',
            ],

            'category' => [
                'required',
                'in:Vendor Contract,Receipt,Invoice,Marriage Document,Permit,Photo,Inspiration,Other',
            ],

            'description' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        $file = $request->file('file');

        $path = $file->store(
            'weddings/' . $wedding->id . '/documents',
            'public'
        );

        WeddingDocument::create([
            'wedding_id' => $wedding->id,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_type' => $file->getMimeType(),
            'category' => $validated['category'],
            'description' => $validated['description'] ?? null,
            'file_size' => $file->getSize(),
        ]);

        return redirect()
            ->route('wedding.documents')
            ->with(
                'success',
                'File uploaded successfully.'
            );
    }


    /**
     * Download a wedding file.
     */
    public function download(
        WeddingDocument $document
    ) {

        if (
            $document->wedding->user_id
            !== auth()->id()
        ) {
            abort(403);
        }

        if (
            !Storage::disk('public')
                ->exists($document->file_path)
        ) {
            abort(404);
        }

        return Storage::disk('public')
            ->download(
                $document->file_path,
                $document->file_name
            );
    }


    /**
     * Delete a wedding file.
     */
    public function destroy(
        WeddingDocument $document
    ): RedirectResponse {

        if (
            $document->wedding->user_id
            !== auth()->id()
        ) {
            abort(403);
        }

        if (
            Storage::disk('public')
                ->exists($document->file_path)
        ) {
            Storage::disk('public')
                ->delete($document->file_path);
        }

        $document->delete();

        return redirect()
            ->route('wedding.documents')
            ->with(
                'success',
                'File deleted successfully.'
            );
    }
}