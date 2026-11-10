@extends('layouts.public')

@section('content')
    <section class="section-pad">
        <div class="container-prose max-w-2xl">
            <nav aria-label="Breadcrumb" class="text-sm text-[color:var(--color-charcoal-600)] mb-2">
                <a href="{{ route('home') }}">Home</a> / <span aria-current="page">{{ $heading }}</span>
            </nav>
            <h1>{{ $heading }}</h1>
            <p class="text-lg text-[color:var(--color-charcoal-600)]">{{ $intro }}</p>

            @if (session('flash_ok'))
                <div class="card mt-4" role="status">
                    <p class="m-0 font-semibold text-[color:var(--color-forest-700)]">{{ session('flash_ok') }}</p>
                </div>
            @endif
            @if (session('flash_warn'))
                <div class="card mt-4" role="alert" style="border-left:4px solid var(--color-ochre-600);">
                    <p class="m-0">{{ session('flash_warn') }}</p>
                </div>
            @endif
            @if ($errors->any())
                <div class="card mt-4" role="alert" style="border-left:4px solid #b32020;">
                    <p class="m-0 font-semibold">Please fix the following:</p>
                    <ul class="mt-2 mb-0 pl-5">
                        @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                    </ul>
                </div>
            @endif

            <form method="post" action="{{ route('alerts.subscribe') }}" class="card mt-6" aria-labelledby="subscribe-h">
                @csrf
                <input type="hidden" name="audience" value="{{ $audience }}">
                <h2 id="subscribe-h" class="m-0">Subscribe to {{ $audience }} alerts</h2>
                <p class="text-sm text-[color:var(--color-charcoal-600)] mt-1">We'll send you an email to confirm — the link expires in 24 hours.</p>

                <label class="flex flex-col text-sm mt-4">
                    <span class="font-semibold text-[color:var(--color-forest-700)]">Email<span aria-hidden="true"> *</span></span>
                    <input required type="email" name="email" value="{{ old('email') }}"
                           class="mt-1 border border-[color:var(--color-ivory-100)] rounded p-2 bg-white" autocomplete="email">
                </label>
                <label class="flex flex-col text-sm mt-3">
                    <span class="font-semibold text-[color:var(--color-forest-700)]">Name</span>
                    <input type="text" name="name" value="{{ old('name') }}" maxlength="120"
                           class="mt-1 border border-[color:var(--color-ivory-100)] rounded p-2 bg-white" autocomplete="name">
                </label>
                <label class="flex flex-col text-sm mt-3">
                    <span class="font-semibold text-[color:var(--color-forest-700)]">Mobile (optional)</span>
                    <input type="tel" name="mobile" value="{{ old('mobile') }}" maxlength="32"
                           class="mt-1 border border-[color:var(--color-ivory-100)] rounded p-2 bg-white" autocomplete="tel">
                </label>
                <p class="mt-5"><button type="submit" class="btn-primary">Send verification email</button></p>
                <p class="text-xs text-[color:var(--color-charcoal-600)] mt-2">By subscribing you consent to receive {{ $audience }} alert emails from this publisher. You can unsubscribe at any time.</p>
            </form>

            <form method="post" action="{{ route('result.register') }}" class="card mt-6" aria-labelledby="result-h">
                @csrf
                <input type="hidden" name="audience" value="{{ $audience }}">
                <h2 id="result-h" class="m-0">Result registration</h2>
                <p class="text-sm text-[color:var(--color-charcoal-600)] mt-1">We collect only: name, mobile, email, exam and roll number. No exam centre is collected.</p>

                <label class="flex flex-col text-sm mt-4">
                    <span class="font-semibold text-[color:var(--color-forest-700)]">Full name<span aria-hidden="true"> *</span></span>
                    <input required type="text" name="rr_name" value="{{ old('rr_name') }}" maxlength="120"
                           class="mt-1 border border-[color:var(--color-ivory-100)] rounded p-2 bg-white">
                </label>
                <label class="flex flex-col text-sm mt-3">
                    <span class="font-semibold text-[color:var(--color-forest-700)]">Mobile<span aria-hidden="true"> *</span></span>
                    <input required type="tel" name="rr_mobile" value="{{ old('rr_mobile') }}" maxlength="32"
                           class="mt-1 border border-[color:var(--color-ivory-100)] rounded p-2 bg-white">
                </label>
                <label class="flex flex-col text-sm mt-3">
                    <span class="font-semibold text-[color:var(--color-forest-700)]">Email<span aria-hidden="true"> *</span></span>
                    <input required type="email" name="rr_email" value="{{ old('rr_email') }}"
                           class="mt-1 border border-[color:var(--color-ivory-100)] rounded p-2 bg-white">
                </label>
                <label class="flex flex-col text-sm mt-3">
                    <span class="font-semibold text-[color:var(--color-forest-700)]">Exam<span aria-hidden="true"> *</span></span>
                    <select required name="rr_exam_id" class="mt-1 border border-[color:var(--color-ivory-100)] rounded p-2 bg-white">
                        <option value="">Select an exam</option>
                        @foreach($exams as $e)
                            <option value="{{ $e['id'] }}" @selected((int) old('rr_exam_id') === (int) $e['id'])>{{ $e['name'] }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="flex flex-col text-sm mt-3">
                    <span class="font-semibold text-[color:var(--color-forest-700)]">Roll number<span aria-hidden="true"> *</span></span>
                    <input required type="text" name="rr_roll" value="{{ old('rr_roll') }}" maxlength="64"
                           class="mt-1 border border-[color:var(--color-ivory-100)] rounded p-2 bg-white">
                </label>
                <p class="mt-5"><button type="submit" class="btn-primary">Submit result registration</button></p>
            </form>

            <div class="mt-8 card">
                <p class="font-semibold m-0 text-[color:var(--color-forest-700)]">Manage subscriptions</p>
                <form method="post" action="{{ route('alerts.unsubscribe') }}" class="mt-3 flex flex-col gap-3 sm:flex-row sm:items-end">
                    @csrf
                    <label class="flex flex-col text-sm flex-1">
                        <span class="font-semibold">Email</span>
                        <input required type="email" name="email" class="mt-1 border border-[color:var(--color-ivory-100)] rounded p-2 bg-white">
                    </label>
                    <label class="flex flex-col text-sm">
                        <span class="font-semibold">Audience</span>
                        <select name="audience" class="mt-1 border border-[color:var(--color-ivory-100)] rounded p-2 bg-white">
                            <option value="{{ $audience }}">{{ ucfirst($audience) }} alerts</option>
                        </select>
                    </label>
                    <button type="submit" class="btn-ghost">Unsubscribe</button>
                </form>
                <p class="text-xs text-[color:var(--color-charcoal-600)] mt-2">Unsubscribing adds your address to this audience's suppression list. The other list is unaffected.</p>
            </div>
        </div>
    </section>
@endsection
