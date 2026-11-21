@extends('layouts.public', ['nav' => $audience === 'gd' ? 'gd-alerts' : 'officer-alerts'])

@section('content')
    <div class="pagehead">
        <div class="frame">
            <div class="crumb"><a href="{{ route('home') }}">Home</a> / {{ $audienceLabel }} SMS Alerts</div>
            <h1>{{ $audienceLabel }} — SMS Alerts</h1>
            <p>Subscribe for further notifications on this mobile. Unsubscribe any time using the same mobile. A GD subscription does not affect an Officer subscription (or vice versa).</p>
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

        <div class="alerts-split">
            <article class="alerts-card alerts-card--subscribe">
                <header>
                    <span class="refund-badge">SUBSCRIBE</span>
                    <h2>Subscribe for further notifications</h2>
                </header>
                <p>Fill in your details below. We prefix <strong>+91</strong> to your mobile automatically.</p>

                <form method="post" action="{{ route('alerts.subscribe') }}" class="alerts-form">
                    @csrf
                    <input type="hidden" name="audience" value="{{ $audience }}">

                    <div class="form-row">
                        <label>
                            <span>First name <em>*</em></span>
                            <input required type="text" name="first_name" value="{{ old('first_name') }}" maxlength="80" autocomplete="given-name">
                        </label>
                        <label>
                            <span>Last name</span>
                            <input type="text" name="last_name" value="{{ old('last_name') }}" maxlength="80" autocomplete="family-name">
                        </label>
                    </div>

                    <label>
                        <span>Mobile <em>*</em></span>
                        <div class="mobile-input">
                            <span class="mobile-prefix" aria-hidden="true">+91</span>
                            <input required type="tel" name="mobile" value="{{ old('mobile') }}" maxlength="10" pattern="[6-9][0-9]{9}" inputmode="numeric" autocomplete="tel-national" placeholder="10-digit number">
                        </div>
                    </label>

                    <label>
                        <span>Email <em>*</em></span>
                        <input required type="email" name="email" value="{{ old('email') }}" autocomplete="email">
                    </label>

                    <button type="submit" class="button">Subscribe for alerts <span aria-hidden="true">→</span></button>
                    <p class="fine">By subscribing you consent to receive {{ $audienceLabel }} notifications. Unsubscribe any time.</p>
                </form>
            </article>

            <article class="alerts-card alerts-card--unsubscribe">
                <header>
                    <span class="refund-badge refund-badge--warn">UNSUBSCRIBE</span>
                    <h2>Unsubscribe from further notifications</h2>
                </header>
                <p>Enter the registered mobile only — <strong>no email required</strong>. We'll mark this mobile as unsubscribed; your details remain on file so you can resubscribe later.</p>

                <form method="post" action="{{ route('alerts.unsubscribe') }}" class="alerts-form">
                    @csrf
                    <input type="hidden" name="audience" value="{{ $audience }}">

                    <label>
                        <span>Mobile <em>*</em></span>
                        <div class="mobile-input">
                            <span class="mobile-prefix" aria-hidden="true">+91</span>
                            <input required type="tel" name="mobile" maxlength="10" pattern="[6-9][0-9]{9}" inputmode="numeric" autocomplete="tel-national" placeholder="10-digit number">
                        </div>
                    </label>

                    <button type="submit" class="button button--ghost">Unsubscribe</button>
                </form>
            </article>
        </div>

        @if ($resultNotificationEnabled)
            <aside class="alerts-result-cta">
                <div>
                    <p class="eyebrow">TA OFFICE WRITTEN EXAMINATION</p>
                    <h3>Register your roll number for result notifications</h3>
                    <p>Separate from the audience subscription above. Pick the examination, enter your roll number — we'll SMS you when that result is published.</p>
                </div>
                <a href="{{ route('result.form') }}" class="button">Register for result notifications <span aria-hidden="true">→</span></a>
            </aside>
        @endif
    </section>
@endsection
