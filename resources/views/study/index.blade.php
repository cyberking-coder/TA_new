@extends('layouts.public')

@section('content')
    <section class="section-pad">
        <div class="container-prose max-w-2xl">
            <h1>Study materials</h1>
            <p class="text-sm text-[color:var(--color-charcoal-600)]">
                Signed in as order <strong>{{ $buyer->order_ref }}</strong>.
                <form method="post" action="{{ route('study.logout') }}" class="inline">@csrf
                    <button class="text-sm underline">Sign out</button>
                </form>
            </p>

            @if ($materials->isEmpty())
                <div class="card mt-4"><p class="m-0">No study materials match your current entitlements.</p></div>
            @else
                <ul class="list-none p-0 m-0 mt-4">
                    @foreach ($materials as $m)
                        <li class="card mt-3">
                            <p class="font-semibold m-0">{{ $m->title }}</p>
                            @if ($m->description) <p class="m-0 mt-1 text-sm text-[color:var(--color-charcoal-600)]">{{ $m->description }}</p> @endif
                            @if ($m->pdf)
                                <p class="mt-2 m-0">
                                    <a href="{{ app(\App\Http\Controllers\StudyController::class)->signedUrlFor($m->id) }}" class="btn-ghost">
                                        Download PDF ({{ $m->pdf->sizeLabel() }})
                                    </a>
                                    <span class="text-xs block mt-1 text-[color:var(--color-charcoal-600)]">Link is valid for 60 seconds.</span>
                                </p>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </section>
@endsection
