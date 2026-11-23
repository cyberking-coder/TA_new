@extends('layouts.public')

@section('content')
    <div class="pagehead">
        <div class="frame">
            <div class="crumb"><a href="{{ route('home') }}">Home</a> / TA Policies</div>
            <h1>TA Policies</h1>
            <p>Official policy documents issued by the Ministry of Defence and Directorate General Territorial Army.</p>
        </div>
    </div>

    <section class="frame occ">
        <section class="occ-hero">
            <div class="occ-hero-text">
                <p class="eyebrow">OFFICIAL DOCUMENTS</p>
                <h2>Governing policy for the Territorial Army</h2>
                <p>The Territorial Army operates under the <strong>Territorial Army Act, 1948</strong> and the Territorial Army Rules, 1948 — as periodically amended by the Ministry of Defence. The current consolidated policy document is available for download.</p>
            </div>
            <aside class="occ-dates">
                <h3>Document</h3>
                @if ($page->pdf)
                    @php $pdf = $page->pdf; @endphp
                    <dl>
                        <div><dt>File</dt><dd>{{ $pdf->title }}</dd></div>
                        <div><dt>Size</dt><dd>{{ $pdf->sizeLabel() }}</dd></div>
                        <div><dt>Updated</dt><dd>{{ $pdf->updated_at?->format('d M Y') }}</dd></div>
                    </dl>
                    <a class="button" href="{{ route('pdfs.public', $pdf->id) }}" target="_blank" rel="noopener">Open policy PDF <span aria-hidden="true">→</span></a>
                @else
                    <p class="fine" style="color:var(--muted);font-size:13px;margin:0">No policy PDF is attached yet. Admins can attach one from <code>/admin → Pages → TA Policies → Attached PDF</code>.</p>
                @endif
            </aside>
        </section>

        <section class="occ-block">
            <p class="eyebrow">KEY AMENDMENTS</p>
            <h2>Recent amendments to TA Rules</h2>
            <div class="occ-cards">
                <article>
                    <strong>SRO 64 (29 Apr 2023)</strong>
                    <p>Territorial Army (Amendment) Regulations, 2023 — counting of commissioned service for increments of pay; Pre-Commissioning Training period excluded.</p>
                </article>
                <article>
                    <strong>SRO 65 (29 Apr 2023)</strong>
                    <p>Territorial Army (Amendment) Rules, 2023 — military training categories (Recruit / Annual / Pre- &amp; Post-Commission / Voluntary / Permanent Staff). Pre-Commission Training up to 23 weeks.</p>
                </article>
                <article>
                    <strong>Ex-officer exemption</strong>
                    <p>Ex-officers commissioned through ASB may be exempted from Pre-Commission Training if DG TA is satisfied with their prior Armed Forces training.</p>
                </article>
            </div>
            <p style="margin-top:12px" class="fine">For the complete text of the Act, Rules and amendments, refer to the attached PDF on this page. Where the PDF and web content differ, the PDF is authoritative.</p>
        </section>
    </section>
@endsection
