@php
    // Detect whether we're rendering inside the login's simple layout, which
    // now has a dark background. We fall back to ink for the regular panel
    // chrome (where the sidebar colour is lighter in stock Filament).
    $onLogin = request()->routeIs('filament.admin.auth.login');
    $text    = $onLogin ? '#f3f0e3' : 'var(--ta-ink)';
    $sub     = $onLogin ? '#9CAF88' : 'var(--ta-brass-dark)';
@endphp
<div class="flex items-center gap-3">
    <span class="inline-grid h-9 w-9 flex-shrink-0 place-items-center rounded-md"
          style="background: {{ $onLogin ? '#9CAF88' : '#f3f1e4' }};">
        <img src="{{ asset('assets/mark.svg') }}" alt="" width="22" height="26">
    </span>
    <div class="flex flex-col leading-tight">
        <span class="text-[15px] font-semibold tracking-tight" style="color: {{ $text }};">Territorial Army</span>
        <span class="mt-0.5 text-[10px] font-semibold uppercase tracking-[0.22em]" style="color: {{ $sub }};">Admin Console</span>
    </div>
</div>
