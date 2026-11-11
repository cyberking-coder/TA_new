<?php

namespace App\Http\Controllers;

use App\Models\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Public PDF download.
 *
 * Private PDFs are NEVER served from here — buyer-only files live behind
 * /study/download/{material} with entitlement + signed-URL checks.
 *
 * For a public PDF we:
 *   1. Confirm the row is public.
 *   2. Confirm the object exists on the `public` disk.
 *   3. If the disk supports presigned URLs (R2 / S3), redirect to a
 *      signed 30-minute URL so the browser pulls the bytes directly
 *      from Cloudflare — no PHP bandwidth used.
 *   4. Otherwise stream the file through the app (local dev or any disk
 *      whose driver doesn't expose temporaryUrl()).
 *
 * This keeps the R2 bucket private while still letting the public site
 * serve PDFs, which fixes the "browser downloads an S3 XML error"
 * behaviour you hit when the bucket's Public Access is off.
 */
class PdfDownloadController extends Controller
{
    public function publicDownload(string $id)
    {
        $pdf = Pdf::query()->findOrFail((int) $id);

        if ($pdf->isPrivate()) {
            abort(403, 'Private files are served only to verified buyers.');
        }

        $disk = Storage::disk('public');

        if (!$disk->exists($pdf->path)) {
            abort(404);
        }

        // Prefer a presigned URL where the driver supports it (R2 via s3).
        try {
            if (method_exists($disk, 'temporaryUrl')) {
                $url = $disk->temporaryUrl($pdf->path, now()->addMinutes(30), [
                    'ResponseContentType' => 'application/pdf',
                    'ResponseContentDisposition' => 'inline; filename="'.$this->safeName($pdf->title).'.pdf"',
                ]);
                return redirect()->away($url, 302);
            }
        } catch (\Throwable $e) {
            // Fall through to streaming.
            report($e);
        }

        // Fallback: stream the file through PHP.
        return $disk->response($pdf->path, $this->safeName($pdf->title).'.pdf', [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$this->safeName($pdf->title).'.pdf"',
        ]);
    }

    private function safeName(string $title): string
    {
        $clean = preg_replace('/[^A-Za-z0-9 _\-]+/', '', $title);
        $clean = preg_replace('/\s+/', '-', trim($clean));
        return $clean ?: 'document';
    }
}
