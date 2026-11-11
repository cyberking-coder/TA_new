@extends('layouts.public', ['nav' => $nav])

@section('content')
    <div class="pagehead">
        <div class="wrap">
            <div class="crumb"><a href="{{ route('home') }}">Home</a> / {{ $heading }}</div>
            <h1>{{ $heading }}</h1>
            <p>{{ $blurb }}</p>
        </div>
    </div>

    <section class="wrap panel">
        @if ($pdfs->isEmpty())
            <div class="reading">
                <p>No papers have been published yet. Check back soon — new PDFs appear here as soon as they're approved in the admin.</p>
                <p class="fine">Staff: upload a PDF at <a href="/admin/pdfs">Content → PDFs</a>, set <strong>Access = Public</strong> and <strong>Where to list = {{ $heading }}</strong>.</p>
            </div>
        @else
            <div class="resource-grid">
                @foreach ($pdfs as $p)
                    <a class="quick" href="{{ route('pdfs.public', $p->id) }}" target="_blank" rel="noopener">
                        <span class="qicon">
                            <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M7 3h7l5 5v13H7z"/><path d="M14 3v5h5M9 13h6M9 17h6"/>
                            </svg>
                        </span>
                        <strong>{{ $p->title }} <x-new-badge :until="$p->new_until" /></strong>
                        <span>PDF · {{ $p->sizeLabel() }} · updated {{ $p->updated_at?->format('d M Y') }}</span>
                        <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M12 3v12m0 0-4-4m4 4 4-4M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2"/>
                        </svg>
                    </a>
                @endforeach
            </div>
        @endif
    </section>
@endsection
