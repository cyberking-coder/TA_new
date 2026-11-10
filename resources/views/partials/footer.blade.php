<footer class="mt-12 border-t border-[color:var(--color-ivory-100)] bg-white">
    <div class="container-prose py-8 grid gap-6 md:grid-cols-3 text-sm">
        <div>
            <p class="font-display font-semibold text-[color:var(--color-forest-700)] m-0">Territorial Army exam guide</p>
            <p class="text-[color:var(--color-charcoal-600)] mt-1 mb-0">
                Independent exam-information and books website. Not affiliated with the Ministry of Defence or any government body.
            </p>
        </div>
        <div>
            <p class="font-semibold text-[color:var(--color-forest-700)] m-0">Site</p>
            <ul class="list-none p-0 mt-2 space-y-1">
                <li><a href="{{ route('notices.index') }}">Notices &amp; results</a></li>
                <li><a href="{{ route('prep.previous') }}">Preparation</a></li>
                <li><a href="{{ route('books.show', 'tac-examination-book') }}">The book</a></li>
            </ul>
        </div>
        <div>
            <p class="font-semibold text-[color:var(--color-forest-700)] m-0">Policies</p>
            <ul class="list-none p-0 mt-2 space-y-1">
                <li><a href="{{ url('/policies/privacy') }}">Privacy</a></li>
                <li><a href="{{ url('/policies/terms') }}">Terms</a></li>
                <li><a href="{{ url('/policies/refund') }}">Refund &amp; delivery</a></li>
            </ul>
        </div>
    </div>
    <div class="container-prose border-t border-[color:var(--color-ivory-100)] py-4 text-xs text-[color:var(--color-charcoal-600)]">
        &copy; {{ date('Y') }} Territorial Army exam guide — independent publisher.
    </div>
</footer>
