<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">Quick actions</x-slot>
        <x-slot name="description">Common tasks. Click a card to jump straight in.</x-slot>

        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($actions as $a)
                <a href="{{ $a['url'] }}"
                   class="ta-quick group flex items-start gap-3 rounded-md border border-[color:var(--ta-edge)] bg-white p-4 transition hover:border-[color:var(--ta-brass-dark)] hover:shadow-md">
                    <span class="ta-quick-ico mt-0.5 inline-grid h-9 w-9 flex-shrink-0 place-items-center rounded-md bg-[color:var(--ta-paper-2)] text-[color:var(--ta-ink)] transition group-hover:bg-[color:var(--ta-brass)] group-hover:text-[color:var(--ta-ink)]">
                        <x-filament::icon :icon="$a['icon']" class="h-5 w-5" />
                    </span>
                    <span class="min-w-0 flex-1">
                        <span class="flex items-center justify-between gap-2">
                            <span class="font-serif text-[15px] font-medium text-[color:var(--ta-ink)]">{{ $a['label'] }}</span>
                            @if ($a['badge'])
                                <span class="shrink-0 rounded bg-[color:var(--ta-paper-2)] px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wider text-[color:var(--ta-brass-dark)]">{{ $a['badge'] }}</span>
                            @endif
                        </span>
                        <span class="mt-1 block text-[13px] leading-snug text-gray-500">{{ $a['desc'] }}</span>
                    </span>
                </a>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
