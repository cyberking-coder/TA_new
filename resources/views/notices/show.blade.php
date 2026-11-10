@extends('layouts.public', ['nav' => 'notices'])

@section('content')
    <div class="pagehead">
        <div class="wrap">
            <div class="crumb"><a href="{{ route('home') }}">Home</a> / <a href="{{ route('notices.index') }}">Notices</a> / {{ $notice['title'] }}</div>
            <h1>{{ $notice['title'] }}</h1>
            <p>Published {{ \Carbon\Carbon::parse($notice['date'])->format('d F Y') }} · {{ $notice['exam_name'] ?? '' }}</p>
        </div>
    </div>

    <section class="wrap panel">
        <article class="reading">
            <p>{{ $notice['summary'] }}</p>
            @if (!empty($notice['body']))
                <p>{{ $notice['body'] }}</p>
            @endif

            <div style="margin-top:24px;padding:20px;background:#efeee3;border:1px solid var(--line);border-radius:7px;">
                <div class="kicker"><i></i>OFFICIAL SOURCE</div>
                <p style="margin-top:8px">This notice is attributed to <strong>{{ $notice['source_label'] }}</strong>. Open the source document below.</p>
                @if (!empty($notice['source_url']) && $notice['source_url'] !== '#')
                    <a class="btn" href="{{ $notice['source_url'] }}" rel="noopener external" target="_blank">
                        Open source document (PDF{{ !empty($notice['source_size']) ? ', '.$notice['source_size'] : '' }})
                        <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 18 18 6M6 6h12v12"/></svg>
                    </a>
                @endif
            </div>

            <h2 style="font:700 24px var(--display);margin:28px 0 10px">Related</h2>
            <ul>
                <li><a href="{{ route('prep.previous') }}">Previous papers for {{ $notice['exam_name'] ?? 'this exam' }}</a></li>
                <li><a href="{{ route('alerts.officer') }}">Subscribe to officer exam alerts</a></li>
                <li><a href="{{ route('alerts.soldier') }}">Subscribe to soldier exam alerts</a></li>
            </ul>
        </article>
    </section>
@endsection
