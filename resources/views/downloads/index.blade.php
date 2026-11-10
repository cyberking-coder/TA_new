@extends('layouts.public', ['nav' => 'downloads'])

@section('content')
    <div class="pagehead">
        <div class="wrap">
            <div class="crumb"><a href="{{ route('home') }}">Home</a> / Important downloads</div>
            <h1>Important downloads</h1>
            <p>Publicly available PDFs. Buyer-only study material is handled separately — see <a href="{{ route('study.login') }}">Study access</a>.</p>
        </div>
    </div>

    <section class="wrap panel">
        @if ($pdfs->isEmpty())
            <p class="count">No public downloads have been published yet.</p>
        @else
            <div class="resource-grid">
                @foreach ($pdfs as $p)
                    <a class="quick" href="{{ route('pdfs.public', $p->id) }}">
                        <span class="qicon">
                            <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 3h7l5 5v13H7z"/><path d="M14 3v5h5M9 13h6M9 17h6"/></svg>
                        </span>
                        <strong>{{ $p->title }}</strong>
                        <span>PDF · {{ $p->sizeLabel() }} · updated {{ $p->updated_at?->format('d M Y') }}</span>
                        <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3v12m0 0-4-4m4 4 4-4M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2"/></svg>
                    </a>
                @endforeach
            </div>
        @endif
    </section>
@endsection
