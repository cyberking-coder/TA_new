@extends('layouts.public', [
    'title'       => 'SSB — 5 Days Walkthrough · Territorial Army Field Guide',
    'description' => 'Day-by-day account of the Service Selection Board process. Each day screens, tests or interviews you against Officer-Like Qualities.',
])

@section('content')
    <div class="pagehead">
        <div class="frame">
            <div class="crumb"><a href="{{ route('home') }}">Home</a> / SSB / 5 Days Video</div>
            <h1>SSB — 5 Days Walkthrough</h1>
            <p>Day-by-day account of the Service Selection Board process. Each day screens, tests or interviews you against Officer-Like Qualities.</p>
        </div>
    </div>

    <section class="frame occ">
        <section class="occ-hero">
            <div class="occ-hero-text">
                <p class="eyebrow">OVERVIEW</p>
                <h2>Five days, three assessors, one question</h2>
                <p>From the moment you report at the Selection Centre to the Conference on day 5, everything is about one question — <strong>do you have Officer-Like Qualities, or can you be trained to acquire them?</strong></p>
                <p>Three assessors work in parallel. The <strong>Interviewing Officer</strong> reads your background, the <strong>Psychologist</strong> reads your natural written responses, the <strong>GTO</strong> reads your behaviour in teams. Day 5 brings them together.</p>
            </div>
            <aside class="occ-dates">
                <h3>At a glance</h3>
                <dl>
                    <div><dt>Total duration</dt><dd>5 days</dd></div>
                    <div><dt>Assessors</dt><dd>IO · Psych · GTO</dd></div>
                    <div><dt>Boards per month</dt><dd>5 batches, ~25 days of testing</dd></div>
                    <div><dt>Result</dt><dd>Announced at Day 5 Conference</dd></div>
                </dl>
            </aside>
        </section>

        <section class="occ-block">
            <p class="eyebrow">DAY BY DAY</p>
            <h2>What happens each day</h2>
            <ol class="occ-pipeline">
                <li>
                    <span class="occ-pipeline-num">01</span>
                    <strong>Day 1 — Screening</strong>
                    <p>Reporting at the Selection Centre, documentation, PIQ submission, Intelligence Test (OIR) and Picture Perception &amp; Discussion Test (PP&amp;DT). <strong>Candidates who do not clear Day 1 are sent back the same evening.</strong></p>
                </li>
                <li>
                    <span class="occ-pipeline-num">02</span>
                    <strong>Day 2 — Psychological Tests</strong>
                    <p>Thematic Apperception Test (TAT), Word Association Test (WAT), Situation Reaction Test (SRT) and Self-Description Test. Timed pen-and-paper — your <strong>natural</strong> responses are what the Psychologist evaluates.</p>
                </li>
                <li>
                    <span class="occ-pipeline-num">03</span>
                    <strong>Day 3 — GTO Series (Part 1)</strong>
                    <p>Group Discussion (GD), Group Planning Exercise (GPE), Progressive Group Task (PGT), Half Group Task (HGT) and Group Obstacle Race ("snake race"). A group of 8–10 candidates work together under the Group Testing Officer.</p>
                </li>
                <li>
                    <span class="occ-pipeline-num">04</span>
                    <strong>Day 4 — GTO Series (Part 2) + Interview</strong>
                    <p>Individual Obstacle (IO), Command Task, Final Group Task (FGT) and Lecturette. In parallel, each candidate is called for the <strong>Personal Interview</strong> — roughly 30 to 60 minutes covering your background, career, family, current affairs and views.</p>
                </li>
                <li>
                    <span class="occ-pipeline-num">05</span>
                    <strong>Day 5 — Conference &amp; Result</strong>
                    <p>Each candidate briefly appears before the full Board — the three assessors (IO, Psych, GTO) and the President. <strong>Recommended candidates stay back for the medical board</strong>; the rest receive a thorough closure briefing and are dispersed.</p>
                </li>
            </ol>
        </section>

        <section class="occ-block">
            <p class="eyebrow">VIDEO WALKTHROUGH</p>
            <h2>Watch the full five-day walkthrough</h2>
            <div class="syl-rules">
                <article class="syl-rule">
                    <h3>Official DGTA explainer videos</h3>
                    <p>The official DG TA YouTube channel has day-wise explainer videos covering Days 1 through 5 of the SSB — from reporting day right through to the Conference. Each video is produced by serving officers and walks through the exact rooms, tests and testing style you will encounter.</p>
                    <p><strong>Attach the embedded walkthrough video here</strong> from /admin → Pages → ssb-5-days-video once the video file is uploaded.</p>
                </article>
                <article class="syl-rule syl-rule--warn">
                    <h3>Watch with intent</h3>
                    <p>The videos are reference, not rehearsal material. Trained assessors immediately catch pre-structured or coached answers. Use the walkthrough to <strong>understand the flow</strong>; let your personality respond in real time.</p>
                </article>
            </div>
        </section>
    </section>
@endsection
