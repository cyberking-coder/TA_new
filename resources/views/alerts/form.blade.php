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

            <div class="formbox" style="border-left:4px solid var(--gold)">
                <div class="kicker"><i></i>STEP 1 · VERIFY MOBILE FOR REGISTRATION</div>
                <h2>Verify mobile for registration</h2>
                <p class="fine">Request a 6-digit OTP on the mobile you're going to register. After you verify it, you have 30 minutes to submit the registration form below.</p>

                <form method="post" action="{{ route('result.otp.send') }}" style="display:flex;flex-wrap:wrap;gap:10px;align-items:end">
                    @csrf
                    <input type="hidden" name="audience" value="{{ $audience }}">
                    <label style="flex:1;min-width:220px">
                        <span>Mobile (10 digits, Indian)</span>
                        <input required type="tel" name="mobile" maxlength="20" inputmode="tel" autocomplete="tel">
                    </label>
                    <button type="submit" class="btn">Send OTP</button>
                </form>

                <form method="post" action="{{ route('result.otp.verify') }}" style="display:flex;flex-wrap:wrap;gap:10px;align-items:end;margin-top:14px">
                    @csrf
                    <input type="hidden" name="audience" value="{{ $audience }}">
                    <label style="flex:1;min-width:220px">
                        <span>Same mobile</span>
                        <input required type="tel" name="mobile" maxlength="20" inputmode="tel" autocomplete="tel">
                    </label>
                    <label style="min-width:140px">
                        <span>6-digit OTP</span>
                        <input required type="text" name="code" maxlength="6" pattern="\d{6}" inputmode="numeric" autocomplete="one-time-code" style="max-width:140px;letter-spacing:.3em">
                    </label>
                    <button type="submit" class="btn" style="background:var(--green);color:#fff">Verify</button>
                </form>
                <p class="fine" style="margin-top:8px">Max 3 OTPs per mobile per hour. Once verified, your mobile stays verified for 30 minutes.</p>
            </div>

            <form class="formbox" method="post" action="{{ route('result.register') }}" id="demoform">
                @csrf
                <input type="hidden" name="audience" value="{{ $audience }}">
                <div class="kicker"><i></i>STEP 2 · RESULT REGISTRATION</div>
                <h2>Result registration</h2>
                <p class="fine">We collect only name, mobile, email, exam and roll number. No exam centre is collected. Use the <strong>same mobile</strong> you verified above.</p>
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

            <div class="formbox" style="border-left:4px solid var(--gold)">
                <div class="kicker"><i></i>MOBILE VERIFY</div>
                <h2>Verify your mobile for SMS alerts</h2>
                <p class="fine">Enter your mobile number and request a 6-digit OTP. The code expires in 10 minutes.</p>

                <form method="post" action="{{ route('alerts.otp.send') }}" style="display:flex;flex-wrap:wrap;gap:10px;align-items:end">
                    @csrf
                    <input type="hidden" name="audience" value="{{ $audience }}">
                    <label style="flex:1;min-width:220px">
                        <span>Mobile (10 digits, Indian)</span>
                        <input required type="tel" name="mobile" maxlength="20" pattern="[0-9+\s-]{10,20}" inputmode="tel" autocomplete="tel">
                    </label>
                    <button type="submit" class="btn">Send OTP</button>
                </form>

                <form method="post" action="{{ route('alerts.otp.verify') }}" style="display:flex;flex-wrap:wrap;gap:10px;align-items:end;margin-top:14px">
                    @csrf
                    <input type="hidden" name="audience" value="{{ $audience }}">
                    <label style="flex:1;min-width:220px">
                        <span>Same mobile</span>
                        <input required type="tel" name="mobile" maxlength="20" inputmode="tel" autocomplete="tel">
                    </label>
                    <label style="min-width:140px">
                        <span>6-digit OTP</span>
                        <input required type="text" name="code" maxlength="6" pattern="\d{6}" inputmode="numeric" autocomplete="one-time-code" style="max-width:140px;letter-spacing:.3em">
                    </label>
                    <button type="submit" class="btn" style="background:var(--green);color:#fff">Verify</button>
                </form>
                <p class="fine" style="margin-top:8px">Max 3 OTPs per mobile per hour. Codes are stored hashed and auto-expire.</p>
            </div>

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
