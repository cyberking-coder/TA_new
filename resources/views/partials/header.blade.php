<header class="border-b border-[color:var(--color-ivory-100)] bg-white">
    <div class="container-prose flex items-center justify-between py-3 gap-4">
        <a href="{{ route('home') }}" class="font-display font-semibold text-[color:var(--color-forest-700)] text-lg no-underline">
            Territorial Army <span class="text-[color:var(--color-charcoal-600)] font-normal">exam guide</span>
        </a>
        <nav aria-label="Primary">
            <ul class="flex flex-wrap gap-4 md:gap-6 list-none m-0 p-0 text-[0.95rem]">
                <li><a href="{{ route('notices.index') }}">Notices</a></li>
                <li><a href="{{ route('downloads.index') }}">Downloads</a></li>
                <li><a href="{{ route('prep.previous') }}">Prepare</a></li>
                <li><a href="{{ route('books.show', 'tac-examination-book') }}">Books</a></li>
                <li><a href="{{ route('study.login') }}">Study login</a></li>
            </ul>
        </nav>
    </div>
</header>
