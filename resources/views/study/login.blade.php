@extends('layouts.public')

@section('content')
    <section class="section-pad">
        <div class="container-prose max-w-md">
            <h1>Study login</h1>
            <p class="text-sm text-[color:var(--color-charcoal-600)]">
                Sign in with your order reference (e.g. <code>TA-ABCD1234</code>) and the password you set after your purchase.
            </p>

            @if ($errors->any())
                <div class="card mt-4" role="alert" style="border-left:4px solid #b32020;">
                    <ul class="m-0 pl-5">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif

            <form method="post" action="{{ route('study.authenticate') }}" class="card mt-4 grid gap-3">
                @csrf
                <label class="flex flex-col text-sm">
                    <span class="font-semibold">Order reference</span>
                    <input required type="text" name="order_ref" value="{{ old('order_ref') }}" maxlength="64"
                           class="mt-1 border border-[color:var(--color-ivory-100)] rounded p-2 bg-white">
                </label>
                <label class="flex flex-col text-sm">
                    <span class="font-semibold">Password</span>
                    <input required type="password" name="password" maxlength="72"
                           class="mt-1 border border-[color:var(--color-ivory-100)] rounded p-2 bg-white">
                </label>
                <p class="mt-2"><button type="submit" class="btn-primary">Sign in</button></p>
            </form>

            <p class="mt-4 text-sm">
                First time? Use the password-setup link emailed with your receipt. If it expired, contact support — a new link can be issued.
            </p>
        </div>
    </section>
@endsection
