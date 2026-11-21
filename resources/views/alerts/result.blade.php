@extends('layouts.public')

@section('content')
    <div class="pagehead">
        <div class="frame">
            <div class="crumb"><a href="{{ route('home') }}">Home</a> / TA Office Written Examination Result Registration</div>
            <h1>Result registration</h1>
            <p>Register to get an SMS the moment the TA Office Written Examination result for your examination is published.</p>
        </div>
    </div>

    <section class="frame info-page">
        @if (session('flash_ok'))
            <div class="reading" role="status" style="border-left:4px solid #4a7a3f;margin-bottom:20px"><p style="margin:0"><strong>{{ session('flash_ok') }}</strong></p></div>
        @endif
        @if (session('flash_warn'))
            <div class="reading" role="alert" style="border-left:4px solid #ba7619;margin-bottom:20px"><p style="margin:0">{{ session('flash_warn') }}</p></div>
        @endif
        @if ($errors->any())
            <div class="reading" role="alert" style="border-left:4px solid #b32020;margin-bottom:20px">
                <p style="margin:0"><strong>Please fix the following:</strong></p>
                <ul>@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <article class="alerts-card alerts-card--subscribe" style="max-width:640px">
            <header>
                <span class="refund-badge">RESULT NOTIFICATION</span>
                <h2>Register your examination &amp; roll number</h2>
            </header>
            <p>Only one registration per <strong>examination + roll number</strong> — resubmitting with the same roll updates your details.</p>

            <form method="post" action="{{ route('result.register') }}" class="alerts-form">
                @csrf

                <label>
                    <span>Examination <em>*</em></span>
                    <select required name="rr_exam_id">
                        <option value="">Select an examination</option>
                        @foreach ($exams as $e)
                            <option value="{{ $e->id }}" @selected((int) old('rr_exam_id') === (int) $e->id)>
                                {{ $e->name }}@if ($e->short_code) ({{ $e->short_code }})@endif
                            </option>
                        @endforeach
                    </select>
                    @if ($exams->isEmpty())
                        <small class="fine">No examinations are open for registration at the moment.</small>
                    @endif
                </label>

                <label>
                    <span>Roll number <em>*</em></span>
                    <input required type="text" name="rr_roll" value="{{ old('rr_roll') }}" maxlength="64" autocomplete="off">
                </label>

                <label>
                    <span>Full name <em>*</em></span>
                    <input required type="text" name="rr_name" value="{{ old('rr_name') }}" maxlength="120" autocomplete="name">
                </label>

                <label>
                    <span>Mobile <em>*</em></span>
                    <div class="mobile-input">
                        <span class="mobile-prefix" aria-hidden="true">+91</span>
                        <input required type="tel" name="rr_mobile" value="{{ old('rr_mobile') }}" maxlength="10" pattern="[6-9][0-9]{9}" inputmode="numeric" autocomplete="tel-national" placeholder="10-digit number">
                    </div>
                </label>

                <label>
                    <span>Email <em>*</em></span>
                    <input required type="email" name="rr_email" value="{{ old('rr_email') }}" autocomplete="email">
                </label>

                <button type="submit" class="button" @disabled($exams->isEmpty())>Register for result notification <span aria-hidden="true">→</span></button>
            </form>
        </article>
    </section>
@endsection
