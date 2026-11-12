@extends('layouts.public')

@section('content')
    <section class="section-pad">
        <div class="container-prose max-w-md">
            <h1>Set your password</h1>
            @if ($errors->any())
                <div class="card mt-4" role="alert" style="border-left:4px solid #b32020;">
                    <ul class="m-0 pl-5">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif
            <form method="post" action="{{ url('/study/reset/'.$token) }}" class="card mt-4 grid gap-3">
                @csrf
                <label class="flex flex-col text-sm">
                    <span class="font-semibold">New password</span>
                    <input required type="password" name="password" minlength="10" maxlength="72"
                           class="mt-1 border border-[color:var(--color-ivory-100)] rounded p-2 bg-white">
                </label>
                <label class="flex flex-col text-sm">
                    <span class="font-semibold">Confirm password</span>
                    <input required type="password" name="password_confirmation" minlength="10" maxlength="72"
                           class="mt-1 border border-[color:var(--color-ivory-100)] rounded p-2 bg-white">
                </label>
                <p class="mt-2"><button type="submit" class="btn-primary">Set password</button></p>
            </form>
        </div>
    </section>
@endsection
