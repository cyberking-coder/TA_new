@props(['until' => null])
@php
    // $until may arrive as a Carbon instance, an ISO string, a date-time string,
    // or null. Parse defensively so a bad cache entry can't tank the page.
    $show = false;
    try {
        if ($until) {
            $t = $until instanceof \DateTimeInterface ? $until : \Carbon\Carbon::parse((string) $until);
            $show = $t > now();
        }
    } catch (\Throwable $e) { $show = false; }
@endphp
@if ($show)
    <span class="new-flag" aria-label="Recently added" title="Added recently">NEW</span>
@endif

