@extends('layouts.public')

@section('content')
    <div class="pagehead">
        <div class="frame">
            <div class="crumb"><a href="{{ route('home') }}">Home</a> / SSB / Selection Center Information</div>
            <h1>Selection Centres &amp; the SSB Process</h1>
            <p>Four selection centres, five days of testing: Psychology, GTO, Interview and Conference — how the Service Selection Board evaluates officer-like qualities.</p>
        </div>
    </div>

    <section class="frame occ">
        <section class="occ-hero">
            <div class="occ-hero-text">
                <p class="eyebrow">OVERVIEW</p>
                <h2>Four centres, one goal — officer-like qualities</h2>
                <p>The SSB process is based on selected personality traits and qualities designated as <strong>Officer-Like Qualities (OLQs)</strong>, evolved over decades. Three different techniques — Psychology, Interview and GTO — assess candidates on these qualities. The assessor gauges your present level and then gives you a predictive level you are likely to attain after military training.</p>
            </div>
            <aside class="occ-dates">
                <h3>At a glance</h3>
                <dl>
                    <div><dt>Testing days</dt><dd>5 days</dd></div>
                    <div><dt>Selection centres</dt><dd>4 — Allahabad, Bhopal, Bangalore, Jalandhar</dd></div>
                    <div><dt>Stage I</dt><dd>Intelligence + PP&amp;DT (Day 1)</dd></div>
                    <div><dt>Stage II</dt><dd>Psychology + GTO + Interview</dd></div>
                </dl>
            </aside>
        </section>

        <section class="occ-block">
            <p class="eyebrow">SELECTION CENTRES</p>
            <h2>Where the SSB is held</h2>
            <div class="occ-hq-grid">
                <article>
                    <span class="occ-hq-zone">Selection Centre East</span>
                    <p>Allahabad · raised 1957</p>
                    <small>11, 14, 18, 19 SSB + 34 SSB (Air Force)</small>
                </article>
                <article>
                    <span class="occ-hq-zone">Selection Centre Central</span>
                    <p>Bhopal · established Oct 1979</p>
                    <small>20, 21, 22 + 33 (Naval) SSB</small>
                </article>
                <article>
                    <span class="occ-hq-zone">Selection Centre South</span>
                    <p>Bangalore</p>
                    <small>Modern campus; new design under rollout</small>
                </article>
                <article>
                    <span class="occ-hq-zone">Selection Centre North</span>
                    <p>Jalandhar · raised 01 Jul 2015</p>
                    <small>Caters to female candidates</small>
                </article>
            </div>
        </section>

        <section class="occ-block">
            <p class="eyebrow">STAGE I · DAY 1</p>
            <h2>Screening Test</h2>
            <p>Not every candidate who reports has the desirable qualities to move to Stage II. Stage I filters candidates on the very first day so unsuitable candidates can return home without wasted time.</p>
            <div class="occ-cards">
                <article>
                    <strong>Intelligence Test</strong>
                    <p>A test of logic and analytical aptitude. Candidates are awarded an intelligence rating (I to V) based on correct answers. A higher rating helps in the overall assessment.</p>
                </article>
                <article>
                    <strong>Picture Perception &amp; Discussion</strong>
                    <p>A picture is shown briefly. One minute to note characters, their age, mood and the situation; followed by individual story-telling and a group discussion.</p>
                </article>
            </div>
            <p style="margin-top:14px">Candidates successful in Stage I are retained for subsequent tests. Rejected candidates are returned the next day.</p>
        </section>

        <section class="occ-block">
            <p class="eyebrow">STAGE II · DAYS 2 – 5</p>
            <h2>Psychology · GTO · Interview</h2>
            <ol class="occ-pipeline">
                <li>
                    <span class="occ-pipeline-num">02</span>
                    <strong>Psychological Assessment</strong>
                    <p>Thematic Apperception Test (TAT), Word Association Test (WAT), Situation Reaction Test (SRT) and Self-Description. The psychologist assesses personality from your written responses — <strong>natural</strong>, not pre-structured.</p>
                </li>
                <li>
                    <span class="occ-pipeline-num">03</span>
                    <strong>GTO (Group Testing Officer)</strong>
                    <p>Two days of group tasks — command tasks, group discussion, group planning exercise, progressive and half group tasks, lecturette, individual obstacle, final group task. Project your qualities; do not remain a passenger.</p>
                </li>
                <li>
                    <span class="occ-pipeline-num">04</span>
                    <strong>Personal Interview</strong>
                    <p>Approximately an hour with the Interviewing Officer. Covers your background, education, employment, hobbies, current affairs and views. Be natural, be truthful.</p>
                </li>
                <li>
                    <span class="occ-pipeline-num">05</span>
                    <strong>Conference</strong>
                    <p>On day 5, the full board (IO + Psych + GTO + President) evaluates every candidate as a whole. Recommended candidates stay for the medical board; the rest are given closure briefing and dispersed.</p>
                </li>
            </ol>
        </section>

        <section class="occ-block">
            <p class="eyebrow">CANDIDATE FACILITIES</p>
            <h2>While you are at the Selection Centre</h2>
            <div class="occ-cards">
                <article>
                    <strong>Accommodation</strong>
                    <p>Comfortable accommodation at subsidised rates; mess can feed 150 candidates at a time.</p>
                </article>
                <article>
                    <strong>Billets</strong>
                    <p>Twin-sharing rooms proximate to the testing area; capacity ~160 candidates per batch.</p>
                </article>
                <article>
                    <strong>Connectivity</strong>
                    <p>Internet, printing and photocopy facilities available to keep candidates connected.</p>
                </article>
                <article>
                    <strong>Motivating displays</strong>
                    <p>Information hub about Service branches; serving officers interact and guide aspirants.</p>
                </article>
            </div>
        </section>
    </section>
@endsection
