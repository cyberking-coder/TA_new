@extends('layouts.public')

@section('content')
    <section class="section-pad">
        <div class="container-prose">
            <nav aria-label="Breadcrumb" class="text-sm text-[color:var(--color-charcoal-600)] mb-2">
                <a href="{{ route('home') }}">Home</a> / <span aria-current="page">Notices &amp; downloads</span>
            </nav>
            <h1>Notices &amp; downloads</h1>
            <p class="text-lg text-[color:var(--color-charcoal-600)] max-w-prose">
                Dated recruitment notices, result updates and official documents. Each notice links to its issuing source.
            </p>

            <form method="get" action="{{ route('notices.index') }}" class="mt-6 card grid gap-3 md:grid-cols-4" aria-label="Filter notices">
                <label class="flex flex-col text-sm">
                    <span class="font-semibold text-[color:var(--color-forest-700)]">Exam</span>
                    <select name="exam" class="mt-1 border border-[color:var(--color-ivory-100)] rounded p-2 bg-white">
                        <option value="">All exams</option>
                        @foreach($exams as $e)
                            <option value="{{ $e['slug'] }}" @selected(($filters['exam'] ?? '') === $e['slug'])>{{ $e['name'] }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="flex flex-col text-sm">
                    <span class="font-semibold text-[color:var(--color-forest-700)]">Year</span>
                    <select name="year" class="mt-1 border border-[color:var(--color-ivory-100)] rounded p-2 bg-white">
                        <option value="">All years</option>
                        @foreach($years as $y)
                            <option value="{{ $y }}" @selected((string)($filters['year'] ?? '') === (string)$y)>{{ $y }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="flex flex-col text-sm">
                    <span class="font-semibold text-[color:var(--color-forest-700)]">Type</span>
                    <select name="type" class="mt-1 border border-[color:var(--color-ivory-100)] rounded p-2 bg-white">
                        <option value="">All types</option>
                        <option value="notification" @selected(($filters['type'] ?? '') === 'notification')>Notification</option>
                        <option value="result" @selected(($filters['type'] ?? '') === 'result')>Result</option>
                        <option value="admit-card" @selected(($filters['type'] ?? '') === 'admit-card')>Admit card</option>
                    </select>
                </label>
                <div class="flex items-end gap-2">
                    <button type="submit" class="btn-primary">Apply</button>
                    <a href="{{ route('notices.index') }}" class="btn-ghost">Reset</a>
                </div>
            </form>

            <ul class="list-none p-0 m-0 mt-8">
                @forelse($notices as $notice)
                    <li class="notice-row">
                        <time datetime="{{ $notice['date'] }}">{{ \Carbon\Carbon::parse($notice['date'])->format('d M Y') }}</time>
                        <a href="{{ route('notices.show', $notice['slug']) }}">{{ $notice['title'] }}</a>
                        <span class="badge ml-2">{{ ucfirst($notice['type']) }}</span>
                        <p class="m-0 mt-1 text-sm text-[color:var(--color-charcoal-600)]">{{ $notice['summary'] }}</p>
                    </li>
                @empty
                    <li class="notice-row">No notices match the chosen filters. <a href="{{ route('notices.index') }}">Reset filters</a>.</li>
                @endforelse
            </ul>
        </div>
    </section>
@endsection
