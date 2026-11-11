@props(['until' => null])
@if($until && \Carbon\Carbon::parse($until)->isFuture())
    <span class="new-flag" aria-label="Recently added" title="Added recently">NEW</span>
@endif
