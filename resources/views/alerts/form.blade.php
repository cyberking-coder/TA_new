@extends('layouts.public', ['nav' => 'alerts'])

@section('content')
    <div class="pagehead">
        <div class="wrap">
            <div class="crumb"><a href="{{ route('home') }}">Home</a> / {{ ucfirst($audience) }} alerts</div>
            <h1>{{ ucfirst($audience) }} alerts</h1>
            <p>{{ $intro }}</p>
        </div>
    </div>

    <section class="wrap panel">
        @if (session('flash_ok'))
            <div class="reading" role="status" style="border-left:4px solid var(--green);margin-bottom:24px">
                <p style="margin:0"><strong>{{ session('flash_ok') }}</strong></p>
            </div>
        @endif
        @if (session('flash_warn'))
            <div class="reading" role="alert" style="border-left:4px solid #ba7619;margin-bottom:24px">
                <p style="margin:0">{{ session('flash_warn') }}</p>
            </div>
        @endif
        @if ($errors->any())
            <div class="reading" role="alert" style="border-left:4px solid #b32020;margin-bottom:24px">
                <p style="margin:0"><strong>Please fix the following:</strong></p>
                <ul>@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <div style="display:grid;grid-template-columns:1fr;gap:28px;max-width:730px;">
            <form class="formbox" method="post" action="{{ route('alerts.subscribe') }}">
                @csrf
                <input type="hidden" name="audience" value="{{ $audience }}">
                <div class="kicker"><i></i>SUBSCRIBE</div>
                <h2>Subscribe to {{ $audience }} alerts</h2>
                <p class="fine">We'll send a verification email. The link expires in 24 hours.</p>
                <label><span>Email</span>
                    <input required type="email" name="email" value="{{ old('email') }}" autocomplete="email">
                </label>
                <label><span>Name</span>
                    <input type="text" name="name" value="{{ old('name') }}" maxlength="120" autocomplete="name">
                </label>
                <label><span>Mobile (optional)</span>
                    <input type="tel" name="mobile" value="{{ old('mobile') }}" maxlength="32" autocomplete="tel">
                </label>
                <button type="submit" class="btn">Send verification email</button>
                <p class="fine">By subscribing you consent to receive {{ $audience }} alert emails. Unsubscribe any time.</p>
            </form>

            <form class="formbox" method="post" action="{{ route('result.register') }}" id="demoform">
                @csrf
                <input type="hidden" name="audience" value="{{ $audience }}">
                <div class="kicker"><i></i>RESULT REGISTRATION</div>
                <h2>Result registration</h2>
                <p class="fine">We collect only name, mobile, email, exam and roll number. No exam centre is collected.</p>
                <label><span>Full name</span>
                    <input required type="text" name="rr_name" value="{{ old('rr_name') }}" maxlength="120">
                </label>
                <label><span>Mobile</span>
                    <input required type="tel" name="rr_mobile" value="{{ old('rr_mobile') }}" maxlength="32">
                </label>
                <label><span>Email</span>
                    <input required type="email" name="rr_email" value="{{ old('rr_email') }}">
                </label>
                <label><span>Exam</span>
                    <select required name="rr_exam_id">
                        <option value="">Select an exam</option>
                        @foreach($exams as $e)
                            <option value="{{ $e['id'] }}" @selected((int) old('rr_exam_id') === (int) $e['id'])>{{ $e['name'] }}</option>
                        @endforeach
                    </select>
                </label>
                <label><span>Roll number</span>
                    <input required type="text" name="rr_roll" value="{{ old('rr_roll') }}" maxlength="64">
                </label>
                <button type="submit" class="btn">Submit result registration</button>
                <p id="feedback" hidden>Saved. You can resubmit any time with a different exam or roll number.</p>
            </form>

            <form class="formbox" method="post" action="{{ route('alerts.unsubscribe') }}">
                @csrf
                <div class="kicker"><i></i>MANAGE</div>
                <h2>Unsubscribe</h2>
                <p class="fine">Unsubscribing adds your address to this audience's suppression list. The other list is unaffected.</p>
                <label><span>Email</span>
                    <input required type="email" name="email">
                </label>
                <input type="hidden" name="audience" value="{{ $audience }}">
                <button type="submit" class="btn" style="background:#efeee3;color:var(--ink);border:1px solid var(--line)">Unsubscribe</button>
            </form>
        </div>
    </section>
@endsection
