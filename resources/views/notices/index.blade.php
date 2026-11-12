@extends('layouts.public', ['nav' => 'notices'])

@section('content')
    <div class="pagehead">
        <div class="wrap">
            <div class="crumb"><a href="{{ route('home') }}">Home</a> / Notices &amp; results</div>
            <h1>Notices &amp; results</h1>
            <p>Browse published exam updates and open the original source documents.</p>
        </div>
    </div>

    <section class="wrap panel">
        <form class="filters" method="get" action="{{ route('notices.index') }}" role="search">
            <label class="inputsearch" for="query">
                <span>Search</span>
                <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="6"/><path d="m20 20-4-4"/></svg>
                <input id="query" name="q" value="{{ request('q') }}" placeholder="Search notices" type="search">
            </label>
            <label><span>Type</span>
                <select id="kind" name="type">
                    <option value="">All types</option>
                    <option value="notification" @selected(($filters['type'] ?? '') === 'notification')>Notification</option>
                    <option value="result" @selected(($filters['type'] ?? '') === 'result')>Result</option>
                    <option value="admit-card" @selected(($filters['type'] ?? '') === 'admit-card')>Admit card</option>
                </select>
            </label>
            <label><span>Exam</span>
                <select id="exam" name="exam">
                    <option value="">All exams</option>
                    @foreach($exams as $e)
                        <option value="{{ $e['slug'] }}" @selected(($filters['exam'] ?? '') === $e['slug'])>{{ $e['name'] }}</option>
                    @endforeach
                </select>
            </label>
            <label><span>Year</span>
                <select name="year">
                    <option value="">All years</option>
                    @foreach($years as $y)
                        <option value="{{ $y }}" @selected((string)($filters['year'] ?? '') === (string)$y)>{{ $y }}</option>
                    @endforeach
                </select>
            </label>
            <button type="submit">Apply</button>
            <a href="{{ route('notices.index') }}" id="reset" style="min-height:43px;display:inline-flex;align-items:center;padding:0 15px;">Reset</a>
        </form>

        <p class="count" style="margin-top:18px"><span id="count">{{ count($notices) }} {{ count($notices) === 1 ? 'notice' : 'notices' }}</span></p>

        <div class="entries">
            @forelse($notices as $n)
                @php $date = \Carbon\Carbon::parse($n['date']); @endphp
                <a class="notice-row" data-kind="{{ ucfirst($n['type']) }}" data-exam="{{ strtoupper($n['type']) }}" href="{{ route('notices.show', $n['slug']) }}">
                    <span class="date"><b>{{ $date->format('d') }}</b><small>{{ strtoupper($date->format('M')) }}</small></span>
                    <span class="notice-text">
                        <span class="meta">{{ ucfirst($n['type']) }} <i></i> {{ $date->format('Y') }}</span>
                        <strong>{{ $n['title'] }} <x-new-badge :until="$n['new_until'] ?? null" /></strong>
                    </span>
                    <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 18 18 6M6 6h12v12"/></svg>
                </a>
            @empty
                <p id="empty">No notices match your filters. <a href="{{ route('notices.index') }}">Reset filters</a>.</p>
            @endforelse
            <p id="empty" {{ count($notices) > 0 ? 'hidden' : '' }}>No notices match your filters.</p>
        </div>
    </section>
@endsection
