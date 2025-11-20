<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    /**
     * Multi-page viewer with visibility check.
     */
    public function view(Document $document)
    {
        $user = Auth::user();
        if (! $user) {
            abort(403);
        }

        $roles = method_exists($user, 'getRoleNames') ? $user->getRoleNames()->toArray() : [];
        $allowed = array_merge(['all'], $roles);

        if (! in_array($document->visibility, $allowed, true)) {
            abort(403);
        }

        $pages = $document->pages()->orderBy('sort_order')->orderBy('id')->get();
        $fileUrls = [];
        foreach ($pages as $page) {
            $fileUrls[] = [
                'title' => $page->title,
                'url' => Storage::disk('public')->url($page->file_path),
            ];
        }

        return view('documents.view', [
            'document' => $document,
            'fileUrls' => $fileUrls,
        ]);
    }

    /**
     * Print-friendly view for a document file with visibility check.
     */
    public function print(Document $document)
    {
        $user = Auth::user();
        if (! $user) {
            abort(403);
        }

        $roles = method_exists($user, 'getRoleNames') ? $user->getRoleNames()->toArray() : [];
        $allowed = array_merge(['all'], $roles);

        if (! in_array($document->visibility, $allowed, true)) {
            abort(403);
        }

        // Build list of file URLs from pages only (single-file mode removed)
        $pages = $document->pages()->orderBy('sort_order')->orderBy('id')->get();
        $fileUrls = [];
        foreach ($pages as $page) {
            $fileUrls[] = [
                'title' => $page->title,
                'url' => Storage::disk('public')->url($page->file_path),
            ];
        }

        return view('documents.print', [
            'document' => $document,
            'fileUrls' => $fileUrls,
        ]);
    }

    /**
     * Download all document pages as a ZIP archive (single-file mode removed).
     */
    public function downloadAll(Document $document)
    {
        $user = Auth::user();
        if (! $user) {
            abort(403);
        }

        $roles = method_exists($user, 'getRoleNames') ? $user->getRoleNames()->toArray() : [];
        $allowed = array_merge(['all'], $roles);
        if (! in_array($document->visibility, $allowed, true)) {
            abort(403);
        }

        $files = [];
        $pages = $document->pages()->orderBy('sort_order')->orderBy('id')->get();
        foreach ($pages as $index => $page) {
            if ($page->file_path) {
                $files[] = [
                    'path' => storage_path('app/public/' . $page->file_path),
                    'name' => sprintf('%02d_%s', $index + 1, basename($page->file_path)),
                ];
            }
        }

        if (empty($files)) {
            abort(404, 'Файлы для скачивания не найдены.');
        }

        $zip = new \ZipArchive();
        $zipFile = storage_path('app/temp/' . 'document_' . $document->id . '_' . time() . '.zip');
        if (!is_dir(dirname($zipFile)) && !mkdir(dirname($zipFile), 0777, true) && !is_dir(dirname($zipFile))) {
            abort(500, 'Не удалось создать временную директорию для архива.');
        }

        if ($zip->open($zipFile, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            abort(500, 'Не удалось создать архив.');
        }

        foreach ($files as $file) {
            if (file_exists($file['path'])) {
                $zip->addFile($file['path'], $file['name']);
            }
        }
        $zip->close();

        $downloadName = str($document->title)->slug('_')->limit(50, '')->append('.zip');
        return response()->streamDownload(function () use ($zipFile) {
            readfile($zipFile);
            @unlink($zipFile);
        }, (string) $downloadName, [
            'Content-Type' => 'application/zip',
        ]);
    }
}
