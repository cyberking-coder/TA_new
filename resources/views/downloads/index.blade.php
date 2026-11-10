@extends('layouts.public')

@section('content')
    <section class="section-pad">
        <div class="container-prose">
            <nav aria-label="Breadcrumb" class="text-sm text-[color:var(--color-charcoal-600)] mb-2">
                <a href="{{ route('home') }}">Home</a> / <span aria-current="page">Important downloads</span>
            </nav>
            <h1>Important downloads</h1>
            <p class="text-lg text-[color:var(--color-charcoal-600)] max-w-prose">
                Publicly available PDFs. Open any document directly. Buyer-only study material is handled separately — see the
                <a href="{{ route('study.login') }}">study login</a>.
            </p>

            @if ($pdfs->isEmpty())
                <div class="card mt-6">
                    <p class="m-0">No public downloads have been published yet.</p>
                </div>
            @else
                <ul class="list-none p-0 m-0 mt-6 grid gap-3 md:grid-cols-2">
                    @foreach ($pdfs as $p)
                        <li class="card">
                            <p class="font-semibold m-0 text-[color:var(--color-forest-700)]">{{ $p->title }}</p>
                            <p class="m-0 mt-1 text-sm text-[color:var(--color-charcoal-600)]">
                                PDF · {{ $p->sizeLabel() }} · updated {{ $p->updated_at?->format('d M Y') }}
                            </p>
                            <p class="mt-3 m-0">
                                <a href="{{ route('pdfs.public', $p->id) }}" class="btn-ghost" rel="noopener">
                                    {{ $p->download_label ?: 'Download PDF' }}
                                </a>
                            </p>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </section>
@endsection
