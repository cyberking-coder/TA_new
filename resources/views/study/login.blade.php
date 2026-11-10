@extends('layouts.public', ['nav' => 'study'])

@section('content')
    <div class="pagehead">
        <div class="wrap">
            <div class="crumb"><a href="{{ route('home') }}">Home</a> / Study access</div>
            <h1>Study access</h1>
            <p>Open the study library associated with your exam book order.</p>
        </div>
    </div>

    <section class="wrap panel">
        @if ($errors->any())
            <div class="reading" role="alert" style="border-left:4px solid #b32020;margin-bottom:24px;max-width:550px">
                <ul>@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <form class="formbox" method="post" action="{{ route('study.authenticate') }}">
            @csrf
            <div class="kicker"><i></i>BUYER LOGIN</div>
            <h2>Sign in</h2>
            <p class="fine">Use your order reference (e.g. <code>TA-ABCD1234</code>) and the password you set after your purchase.</p>
            <label><span>Order reference</span>
                <input required type="text" name="order_ref" value="{{ old('order_ref') }}" maxlength="64">
            </label>
            <label><span>Password</span>
                <input required type="password" name="password" maxlength="72">
            </label>
            <button type="submit" class="btn">Sign in</button>
            <p class="fine">First time? Use the password-setup link emailed with your receipt. If it expired, contact support — a new link can be issued.</p>
        </form>
    </section>
@endsection
