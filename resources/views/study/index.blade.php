@extends('layouts.public', ['nav' => 'study'])

@section('content')
    <div class="pagehead">
        <div class="wrap">
            <div class="crumb"><a href="{{ route('home') }}">Home</a> / Study materials</div>
            <h1>Study materials</h1>
            <p>Signed in as order <strong>{{ $buyer->order_ref }}</strong>.
                <form method="post" action="{{ route('study.logout') }}" style="display:inline">@csrf
                    <button class="textlink" style="background:none;border:0;padding:0;font:inherit;color:inherit;text-decoration:underline;cursor:pointer">Sign out</button>
                </form>
            </p>
        </div>
    </div>

    <section class="wrap panel">
        @if ($materials->isEmpty())
            <div class="reading">
                <p>No study materials match your current entitlements. If you believe this is wrong, write to <a href="{{ url('/pages/contact-us') }}">contact us</a> with your order reference.</p>
            </div>
        @else
            <div class="resource-grid">
                @foreach ($materials as $m)
                    <div class="quick" style="cursor:default;align-items:flex-start">
                        <span class="qicon">
                            <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M3 5c3-1 6-1 9 1v14c-3-2-6-2-9-1zM21 5c-3-1-6-1-9 1v14c3-2 6-2 9-1z"/>
                            </svg>
                        </span>
                        <strong>{{ $m->title }}</strong>
                        @if ($m->description)
                            <span>{{ $m->description }}</span>
                        @endif
                        @if ($m->pdf)
                            <p style="margin:12px 0 0">
                                <a href="{{ app(\App\Http\Controllers\StudyController::class)->signedUrlFor($m->id) }}"
                                   target="_blank" rel="noopener"
                                   class="textlink" style="color:var(--green);font-weight:600">
                                    Open PDF ({{ $m->pdf->sizeLabel() }})
                                    <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M14 5h5v5M19 5l-8 8M19 13v5a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h5"/>
                                    </svg>
                                </a>
                            </p>
                            <span style="font-size:12px;color:var(--mute);margin-top:4px">Link opens in a new tab and is valid for 60 seconds.</span>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </section>
@endsection
