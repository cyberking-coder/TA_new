@extends('layouts.public', [
    'title'       => 'Medical Examination Procedure · Territorial Army Field Guide',
    'description' => 'Medical board, appeal medical board (AMB) and review medical board (RMB) — procedure, hospitals and timelines for recommended candidates.',
])

@section('content')
    <div class="pagehead">
        <div class="frame">
            <div class="crumb"><a href="{{ route('home') }}">Home</a> / Join as an Officer / Medical Examination</div>
            <h1>Medical Examination Procedure</h1>
            <p>Medical board, appeal medical board (AMB) and review medical board (RMB) — procedure, hospitals and timelines for recommended candidates.</p>
        </div>
    </div>

    <section class="frame occ">
        <section class="occ-hero">
            <div class="occ-hero-text">
                <p class="eyebrow">OVERVIEW</p>
                <h2>After SSB, the medical board</h2>
                <p>Recommended candidates appear before the medical board after SSB results. The concerned Military Hospital takes <strong>4 – 5 days</strong> to complete the medical board, after which candidates are dispersed.</p>
                <p>The President of the medical board guides candidates through Appeal and Review Medical Board procedures. Candidates may seek advice from the President of a Special Medical Board for review or appeal if they are declared unfit.</p>
            </div>
            <aside class="occ-dates">
                <h3>At a glance</h3>
                <dl>
                    <div><dt>Board duration</dt><dd>4 – 5 days typical</dd></div>
                    <div><dt>Appeal window</dt><dd>Within 42 days of Special Medical Board</dd></div>
                    <div><dt>AMB target</dt><dd>Completed in 1 day</dd></div>
                    <div><dt>RMB decision</dt><dd>With DG AFMS (not a matter of right)</dd></div>
                </dl>
            </aside>
        </section>

        <section class="occ-block">
            <p class="eyebrow">APPEAL MEDICAL BOARD</p>
            <h2>Hospitals where the AMB is held</h2>
            <div class="occ-hq-grid">
                <article><span class="occ-hq-zone">Delhi Cantt</span><p>Base Hospital, Delhi Cantt.</p></article>
                <article><span class="occ-hq-zone">Pune</span><p>Command Hospital, Southern Command</p></article>
                <article><span class="occ-hq-zone">Kolkata</span><p>Command Hospital, Eastern Command</p></article>
                <article><span class="occ-hq-zone">Lucknow</span><p>Command Hospital, Central Command</p></article>
                <article><span class="occ-hq-zone">Chandimandir</span><p>Command Hospital, Western Command</p></article>
                <article><span class="occ-hq-zone">Bangalore</span><p>Command Hospital, Air Force</p></article>
                <article><span class="occ-hq-zone">C/o 56 APO</span><p>Command Hospital, Northern Command</p></article>
                <article><span class="occ-hq-zone">Mumbai</span><p>INHS Ashvini</p></article>
            </div>
            <p style="margin-top:14px">Candidates report for the medical examination within <strong>42 days</strong> from the date last examined by the Special Medical Board, along with the receipted copy of the MRO / TR as directed by the President Medical Board. The endeavour is to complete the Medical Board in <strong>one day</strong>.</p>
        </section>

        <section class="occ-block">
            <p class="eyebrow">REVIEW MEDICAL BOARD</p>
            <h2>If declared unfit by the AMB</h2>
            <ol class="occ-pipeline">
                <li>
                    <span class="occ-pipeline-num">01</span>
                    <strong>Address your request</strong>
                    <p>Write to Recruiting Directorate (Personnel &amp; Coord), Army Headquarters, West Block III, RK Puram, New Delhi — with a copy to the President of the Appeal Medical Board.</p>
                </li>
                <li>
                    <span class="occ-pipeline-num">02</span>
                    <strong>Timeline</strong>
                    <p>Submit the request <strong>within one day</strong> of the Appeal Medical Board being held.</p>
                </li>
                <li>
                    <span class="occ-pipeline-num">03</span>
                    <strong>Routing</strong>
                    <p>The application for RMB is routed through DG AFMS. The decision to grant an RMB is with DG AFMS and is <strong>not a matter of right</strong>.</p>
                </li>
                <li>
                    <span class="occ-pipeline-num">04</span>
                    <strong>Where the RMB is held</strong>
                    <p>RMBs are conducted at R&amp;R Hospital, Delhi Cantt and AFMC, Pune.</p>
                </li>
            </ol>
        </section>

        <section class="occ-block">
            <p class="eyebrow">STAY &amp; DISCIPLINE</p>
            <h2>While the medical is in progress</h2>
            <div class="syl-rules">
                <article class="syl-rule">
                    <h3>Stay at the Selection Centre</h3>
                    <p>After SSB results, recommended candidates stay in the Selection Centres in the lines earmarked for the medical examination by the Military Hospital. Candidates are allotted fresh medical chest numbers for the purpose.</p>
                </article>
                <article class="syl-rule syl-rule--warn">
                    <h3>Later-date AMB</h3>
                    <p>Candidates who go for an Appeal Medical Board, or whose medical examination is delayed due to unavoidable reasons, do so <strong>under their own arrangements</strong>.</p>
                </article>
                <article class="syl-rule">
                    <h3>Chest numbers</h3>
                    <p>Candidates must put on their medical Chest Number (with Red Cross) whenever they are present on the campus of Selection Centres. Proper discipline is essential throughout the stay.</p>
                </article>
            </div>
        </section>
    </section>
@endsection
