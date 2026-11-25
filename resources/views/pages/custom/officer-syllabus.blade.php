@extends('layouts.public', [
    'title'       => 'Syllabus & Pattern — Online Entrance Exam · Territorial Army Field Guide',
    'description' => 'Pattern, duration, qualifying marks, syllabus and venue rules for the Territorial Army Commission (TAC) Computer Based Test.',
])

@section('content')
    <div class="pagehead">
        <div class="frame">
            <div class="crumb"><a href="{{ route('home') }}">Home</a> / Join as an Officer / Syllabus</div>
            <h1>Syllabus &amp; Pattern — Online Entrance Exam</h1>
            <p>Pattern, duration, qualifying marks, syllabus and venue rules for the Territorial Army Commission (TAC) Computer Based Test.</p>
        </div>
    </div>

    <section class="frame occ syl">
        <section class="occ-hero">
            <div class="occ-hero-text">
                <p class="eyebrow">AT A GLANCE</p>
                <h2>Computer Based Test — 100 marks in 2 hours</h2>
                <p>The exam is a 100-question CBT administered in one session, covering Reasoning, General Knowledge and English. The standard is pitched at a graduate of an Indian university.</p>
                <p class="occ-warn"><strong>Important for 12 July 2026:</strong> There will be <strong>no Mathematics section</strong>. Only Reasoning, General Knowledge and English appear on the paper.</p>
            </div>

            <aside class="occ-dates syl-facts">
                <h3>Exam facts</h3>
                <dl>
                    <div><dt>Mode</dt><dd>Computer Based Test (CBT)</dd></div>
                    <div><dt>Duration</dt><dd>Maximum 02 hours</dd></div>
                    <div><dt>Total marks</dt><dd>100</dd></div>
                    <div><dt>Questions</dt><dd>100 (40 + 30 + 30)</dd></div>
                    <div><dt>Negative marking</dt><dd>¹⁄₃ (0.33) per wrong answer</dd></div>
                    <div><dt>Standard</dt><dd>Graduate of an Indian university</dd></div>
                </dl>
            </aside>
        </section>

        <section class="occ-block">
            <p class="eyebrow">PATTERN</p>
            <h2>Section-wise breakdown</h2>
            <div class="occ-table-wrap">
                <table class="occ-pay">
                    <thead>
                        <tr><th>Part</th><th>Subject</th><th>Questions</th><th>Marks</th><th>Time allotted</th></tr>
                    </thead>
                    <tbody>
                        <tr><td>Part 1</td><td>Reasoning</td><td>40</td><td>40</td><td rowspan="3">2 hours<br><span style="font-size:11px;color:var(--muted)">(one session)</span></td></tr>
                        <tr><td>Part 2</td><td>General Knowledge</td><td>30</td><td>30</td></tr>
                        <tr><td>Part 3</td><td>English</td><td>30</td><td>30</td></tr>
                        <tr><td colspan="2" style="text-align:right;font-weight:600">Total</td><td style="font-weight:600">100</td><td style="font-weight:600">100</td></tr>
                    </tbody>
                </table>
            </div>
            <p class="fine" style="margin-top:12px;color:var(--muted);font-size:13px">Qualifying marks in any or all subjects are at the discretion of the Territorial Army Directorate. Questions marked for review are treated as unanswered.</p>
        </section>

        <section class="occ-block">
            <p class="eyebrow">SYLLABUS</p>
            <h2>What each section tests</h2>
            <div class="syl-cards">
                <article class="syl-card">
                    <span class="syl-num">01</span>
                    <strong>Reasoning</strong>
                    <p>Tests your ability to complete sequences and draw logical conclusions from simple patterns of numbers, statements, figures and letters. No special study required — rational thinking under time pressure.</p>
                    <ul class="syl-bul">
                        <li>Series &amp; sequences</li>
                        <li>Statement &amp; conclusion</li>
                        <li>Figure &amp; letter patterns</li>
                    </ul>
                </article>

                <article class="syl-card">
                    <span class="syl-num">02</span>
                    <strong>General Knowledge</strong>
                    <p>Equal weightage to general knowledge and current affairs. Expect questions on everyday scientific observation, history of India, Indian geography and nature — the kind of awareness expected of an educated person.</p>
                    <ul class="syl-bul">
                        <li>Current affairs (national + international)</li>
                        <li>Science &amp; everyday observation</li>
                        <li>Indian history &amp; geography</li>
                    </ul>
                </article>

                <article class="syl-card">
                    <span class="syl-num">03</span>
                    <strong>English</strong>
                    <p>Tests your understanding of English and workmanlike use of words. Grammar correctness, comprehension and vocabulary at a graduate level.</p>
                    <ul class="syl-bul">
                        <li>Reading comprehension</li>
                        <li>Grammar &amp; usage</li>
                        <li>Vocabulary &amp; synonyms / antonyms</li>
                    </ul>
                </article>
            </div>
        </section>

        <section class="occ-block">
            <p class="eyebrow">OBJECTION MANAGEMENT</p>
            <h2>Challenging the answer keys</h2>
            <p>A link for challenging the answer keys goes live on the DG TA website <strong>72 hours after the last date of examination</strong>. The link is live for <strong>only 3 days</strong> and is also mailed to candidates on their registered email so they can download their response sheet.</p>
        </section>

        <section class="occ-block">
            <p class="eyebrow">ON EXAM DAY</p>
            <h2>Candidate conduct</h2>
            <div class="syl-rules">
                <article class="syl-rule syl-rule--warn">
                    <h3>Use of unfair means</h3>
                    <p>Any candidate found using unfair means is immediately disqualified and must give an undertaking to this effect to the invigilator. A refusal to give the undertaking is recorded under the invigilator's signature and treated as serious misconduct.</p>
                </article>

                <article class="syl-rule syl-rule--danger">
                    <h3>Mobile phones are banned</h3>
                    <p>Mobile phones (even switched off), pagers, pen drives, smart watches, cameras, Bluetooth or any other electronic / programmable / communication-capable device is <strong>strictly prohibited</strong> during the examination. Any infringement attracts disciplinary action including a ban from future examinations.</p>
                </article>

                <article class="syl-rule">
                    <h3>Valuables at your own risk</h3>
                    <p>Do not carry mobiles or any valuable / costly items to the venue — safe-keeping arrangements are not provided. The Commission is not responsible for any loss in this regard.</p>
                </article>
            </div>
        </section>

        <section class="occ-block">
            <p class="eyebrow">ELIGIBILITY REMINDER</p>
            <h2>Admit Card is provisional</h2>
            <p>Your candidature at all stages is <strong>purely provisional</strong>, subject to satisfying the eligibility conditions for the commission. The mere issue of an Admit Card does not imply that your candidature has been finally cleared by the competent authority. Verification against your original documents is done only <strong>after you qualify for the SSB interview</strong>.</p>
        </section>

        <section class="occ-cta">
            <div>
                <p class="eyebrow">NEXT STEP</p>
                <h3>Register for result notifications</h3>
                <p>Get an SMS the moment the TAC written examination result is published for your cycle.</p>
            </div>
            <a href="{{ route('result.form') }}" class="button">Register for result notifications <span aria-hidden="true">→</span></a>
        </section>
    </section>
@endsection
