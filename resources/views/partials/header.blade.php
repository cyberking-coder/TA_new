<header class="border-b border-[color:var(--color-ivory-100)] bg-white">
    <div class="container-prose flex items-center justify-between py-3 gap-4 flex-wrap">
        <a href="{{ route('home') }}" class="font-display font-semibold text-[color:var(--color-forest-700)] text-lg no-underline">
            Territorial Army <span class="text-[color:var(--color-charcoal-600)] font-normal">exam guide</span>
        </a>
        <nav aria-label="Primary">
            <ul class="flex flex-wrap gap-4 md:gap-5 list-none m-0 p-0 text-[0.95rem] items-center">
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('notices.index') }}">Notices</a></li>

                <li x-data="{ open: false }" class="relative">
                    <button @click="open = !open" @click.away="open = false" class="flex items-center gap-1" :aria-expanded="open">
                        Life in Army
                        <span aria-hidden="true">▾</span>
                    </button>
                    <ul x-show="open" x-cloak class="absolute left-0 mt-2 bg-white border border-[color:var(--color-ivory-100)] rounded shadow-md min-w-[14rem] list-none p-2 m-0 z-30">
                        <li><a class="block px-3 py-1.5 rounded hover:bg-[color:var(--color-ivory-100)]" href="{{ url('/pages/life-in-army') }}">Overview</a></li>
                        <li><a class="block px-3 py-1.5 rounded hover:bg-[color:var(--color-ivory-100)]" href="{{ url('/pages/what-army-offers-you') }}">What the army offers you</a></li>
                        <li><a class="block px-3 py-1.5 rounded hover:bg-[color:var(--color-ivory-100)]" href="{{ url('/pages/service-benefits') }}">Service benefits</a></li>
                        <li><a class="block px-3 py-1.5 rounded hover:bg-[color:var(--color-ivory-100)]" href="{{ url('/pages/rules-and-regulations') }}">Rules and regulations</a></li>
                    </ul>
                </li>

                <li x-data="{ open: false }" class="relative">
                    <button @click="open = !open" @click.away="open = false" class="flex items-center gap-1" :aria-expanded="open">
                        TA Units
                        <span aria-hidden="true">▾</span>
                    </button>
                    <ul x-show="open" x-cloak class="absolute left-0 mt-2 bg-white border border-[color:var(--color-ivory-100)] rounded shadow-md min-w-[12rem] list-none p-2 m-0 z-30">
                        <li><a class="block px-3 py-1.5 rounded hover:bg-[color:var(--color-ivory-100)]" href="{{ url('/pages/ta-units') }}">Overview</a></li>
                        <li><a class="block px-3 py-1.5 rounded hover:bg-[color:var(--color-ivory-100)]" href="{{ url('/pages/ecological-ta') }}">Ecological TA</a></li>
                        <li><a class="block px-3 py-1.5 rounded hover:bg-[color:var(--color-ivory-100)]" href="{{ url('/pages/departmental-ta') }}">Departmental TA</a></li>
                    </ul>
                </li>

                <li x-data="{ open: false }" class="relative">
                    <button @click="open = !open" @click.away="open = false" class="flex items-center gap-1" :aria-expanded="open">
                        Join
                        <span aria-hidden="true">▾</span>
                    </button>
                    <ul x-show="open" x-cloak class="absolute left-0 mt-2 bg-white border border-[color:var(--color-ivory-100)] rounded shadow-md min-w-[14rem] list-none p-2 m-0 z-30">
                        <li><a class="block px-3 py-1.5 rounded hover:bg-[color:var(--color-ivory-100)]" href="{{ url('/pages/join-as-jawan') }}">Join as a Jawan / JCO</a></li>
                        <li><a class="block px-3 py-1.5 rounded hover:bg-[color:var(--color-ivory-100)]" href="{{ url('/pages/join-as-officer') }}">Join as an Officer</a></li>
                    </ul>
                </li>

                <li><a href="{{ url('/pages/ta-policies') }}">TA Policies</a></li>
                <li><a href="{{ route('downloads.index') }}">Important Downloads</a></li>
                <li><a href="{{ url('/pages/ta-command-contacts') }}">Command Contacts</a></li>
                <li><a href="{{ route('books.show', 'tac-examination-book') }}">Books</a></li>
                <li><a href="{{ route('study.login') }}">Study login</a></li>
            </ul>
        </nav>
    </div>
</header>
<style>[x-cloak]{display:none !important}</style>
