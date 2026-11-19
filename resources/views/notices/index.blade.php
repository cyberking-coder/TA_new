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
            <div class="filter-actions">
                <button type="submit" class="button">Apply</button>
                <a href="{{ route('notices.index') }}" id="reset" class="underlink">Reset</a>
            </div>
        </form>

        <p class="count" style="margin-top:18px"><span id="count">{{ count($notices) }} {{ count($notices) === 1 ? 'notice' : 'notices' }}</span></p>

        <div class="notice-list">
            @forelse($notices as $n)
                @php $date = \Carbon\Carbon::parse($n['date']); @endphp
                <a class="notice" data-kind="{{ ucfirst($n['type']) }}" href="{{ route('notices.show', $n['slug']) }}">
                    <time datetime="{{ $date->toDateString() }}">
                        <b>{{ $date->format('d') }}</b>
                        <i>{{ $date->format('M Y') }}</i>
                    </time>
                    <strong>{{ $n['title'] }} <x-new-badge :until="$n['new_until'] ?? null" /></strong>
                    <small>{{ ucfirst($n['type']) }}</small>
                    <span aria-hidden="true">&rarr;</span>
                </a>
            @empty
                <p id="empty" class="empty-state">No notices match your filters. <a href="{{ route('notices.index') }}">Reset filters</a>.</p>
            @endforelse
        </div>
    </section>
@endsection
