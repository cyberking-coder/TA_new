<?php

namespace App\Http\Controllers;

use App\Models\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Public PDF download: redirects to the public-disk URL.
 * Private PDFs are NEVER served from here — they live behind the study-material
 * entitlement check in Phase 4 (`GET /study/download/{material}`).
 */
class PdfDownloadController extends Controller
{
    public function publicDownload(string $id)
    {
        $pdf = Pdf::query()->findOrFail((int) $id);

        if ($pdf->isPrivate()) {
            // Explicit refusal: anonymous visitors cannot reach a private PDF through this route.
            abort(403, 'Private files are served only to verified buyers.');
        }

        if (!Storage::disk('public')->exists($pdf->path)) {
            abort(404);
        }

        return redirect(Storage::disk('public')->url($pdf->path), 302);
    }
}
