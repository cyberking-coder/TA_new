@if (!empty($items) && count($items) > 0)
<div class="newsline" role="region" aria-label="Latest updates">
    <div class="wrap newsline-wrap">
        <span class="newsline-label">Latest</span>
        <div class="newsline-track" aria-hidden="false">
            {{-- Rendered twice back-to-back so the CSS transform:translateX(-50%) loops seamlessly. --}}
            <ul class="newsline-row">
                @foreach ($items as $item)
                    <li>
                        <a href="{{ $item['url'] }}" target="_blank" rel="noopener noreferrer">
                            {{ $item['title'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
            <ul class="newsline-row" aria-hidden="true">
                @foreach ($items as $item)
                    <li>
                        <a href="{{ $item['url'] }}" target="_blank" rel="noopener noreferrer" tabindex="-1">
                            {{ $item['title'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endif
