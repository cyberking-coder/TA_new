@if (!empty($items) && count($items) > 0)
<div class="newsline" role="region" aria-label="Latest updates">
    <div class="wrap newsline-wrap">
        <span class="newsline-label">Latest</span>
        <ul>
            @foreach ($items as $item)
                <li>
                    <a href="{{ $item['url'] }}" rel="noopener">
                        {{ $item['title'] }}
                        <x-new-badge :until="$item['new_until'] ?? null" />
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>
@endif
