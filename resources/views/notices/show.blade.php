@extends('layouts.public')

@section('content')
    <article class="section-pad">
        <div class="container-prose max-w-3xl">
            <nav aria-label="Breadcrumb" class="text-sm text-[color:var(--color-charcoal-600)] mb-2">
                <a href="{{ route('home') }}">Home</a> /
                <a href="{{ route('notices.index') }}">Notices &amp; downloads</a> /
                <span aria-current="page">{{ $notice['title'] }}</span>
            </nav>

            <p class="badge">{{ ucfirst($notice['type']) }}</p>
            <h1 class="mt-3">{{ $notice['title'] }}</h1>
            <p class="text-sm text-[color:var(--color-charcoal-600)] mt-2">
                Published <time datetime="{{ $notice['date'] }}">{{ \Carbon\Carbon::parse($notice['date'])->format('d F Y') }}</time>
                &middot; Exam: {{ $notice['exam_name'] }}
            </p>

            <div class="mt-5 text-lg">
                <p>{{ $notice['summary'] }}</p>
                <p>{{ $notice['body'] }}</p>
            </div>

            <div class="card mt-6">
                <p class="font-semibold m-0 text-[color:var(--color-forest-700)]">Official source</p>
                <p class="m-0 mt-1 text-sm text-[color:var(--color-charcoal-600)]">
                    This notice is attributed to <strong>{{ $notice['source_label'] }}</strong>. Open the source document below.
                </p>
                <p class="mt-3">
                    <a href="{{ $notice['source_url'] }}" rel="noopener external" target="_blank" class="btn-ghost">
                        Open source document (PDF, {{ $notice['source_size'] }})
                    </a>
                </p>
            </div>

            <div class="mt-6 card">
                <p class="font-semibold m-0 text-[color:var(--color-forest-700)]">Related</p>
                <ul class="list-disc pl-5 mt-2 m-0 text-sm">
                    <li><a href="{{ route('prep.previous') }}">Previous papers for {{ $notice['exam_name'] }}</a></li>
                    <li><a href="{{ route('alerts.officer') }}">Subscribe to officer-exam alerts</a></li>
                    <li><a href="{{ route('alerts.soldier') }}">Subscribe to soldier-exam alerts</a></li>
                </ul>
            </div>
        </div>
    </article>
@endsection
