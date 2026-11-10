@if (!empty($items) && count($items) > 0)
<div class="bg-[color:var(--color-forest-700)] text-white border-y border-[color:var(--color-forest-600)]">
    <div class="container-prose py-2 flex items-start gap-3 text-sm">
        <span class="badge" style="background:#fff;color:var(--color-forest-700);">Latest</span>
        <ul class="list-none m-0 p-0 flex flex-wrap gap-x-4 gap-y-1">
            @foreach ($items as $item)
                <li>
                    <a href="{{ $item['url'] }}" class="text-white hover:text-[color:var(--color-ochre-500)] underline-offset-2">
                        {{ $item['title'] }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>
@endif
