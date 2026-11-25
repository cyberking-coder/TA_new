@extends('layouts.public', [
    'title'       => 'For Civilian Candidates · Territorial Army Field Guide',
    'description' => 'Apply, write the CBT, clear SSB — everything a gainfully employed civilian needs to know to apply for the Territorial Army Commission (TAC).',
])

@section('content')
    <div class="pagehead">
        <div class="frame">
            <div class="crumb"><a href="{{ route('home') }}">Home</a> / Join as an Officer / For Civilian Candidates</div>
            <h1>For Civilian Candidates</h1>
            <p>Apply, write the CBT, clear SSB — everything a gainfully employed civilian needs to know to apply for the Territorial Army Commission (TAC).</p>
        </div>
    </div>

    <section class="frame occ">
        <section class="occ-hero">
            <div class="occ-hero-text">
                <p class="eyebrow">ABOUT THIS ROUTE</p>
                <h2>Serve in uniform without giving up your profession</h2>
                <p>Applications are invited from gainfully employed citizens for the opportunity of donning the uniform and serving the nation as Territorial Army Officers. You serve in two capacities — as a civilian and as a soldier — without sacrificing your primary profession.</p>
                <p class="occ-warn"><strong>Important:</strong> mere issue of an Admit Card does not mean your candidature has been cleared. Document verification happens only after SSB.</p>
            </div>

            <aside class="occ-dates">
                <h3>Key dates &amp; fee</h3>
                <dl>
                    <div>
                        <dt>Online application window</dt>
                        <dd>20 Apr 2026, 10:00 — 19 May 2026, 23:55</dd>
                    </div>
                    <div>
                        <dt>CBT date</dt>
                        <dd>12 July 2026</dd>
                    </div>
                    <div>
                        <dt>Examination fee</dt>
                        <dd>₹ 500 (non-refundable)</dd>
                    </div>
                    <div>
                        <dt>Vacancies</dt>
                        <dd>Male 11 &middot; Female 01</dd>
                    </div>
                </dl>
                <a class="button" href="https://www.indianarmy.nic.in" target="_blank" rel="noopener">Apply on indianarmy.nic.in <span aria-hidden="true">→</span></a>
            </aside>
        </section>

        <section class="occ-block">
            <p class="eyebrow">ELIGIBILITY</p>
            <h2>Who can apply</h2>
            <div class="occ-cards">
                <article>
                    <strong>Nationality</strong>
                    <p>Only citizens of India — male and female.</p>
                </article>
                <article>
                    <strong>Age</strong>
                    <p>18 to 42 years as on 19 May 2026 (last date of application).</p>
                </article>
                <article>
                    <strong>Education</strong>
                    <p>Graduate from any recognized university.</p>
                </article>
                <article>
                    <strong>Physical standards</strong>
                    <p>Physically and medically fit in all respects.</p>
                </article>
                <article>
                    <strong>Employment</strong>
                    <p>Gainfully employed — Central / State / Semi Govt, PSU, Private, Self-employed or Own business.</p>
                </article>
                <article class="occ-cards-exclude">
                    <strong>Not eligible</strong>
                    <p>Serving members of Regular Army, Navy, Air Force, Police, GREF, Para-Military and similar forces.</p>
                </article>
            </div>
        </section>

        <section class="occ-block">
            <p class="eyebrow">SELECTION PIPELINE</p>
            <h2>How selection works</h2>
            <ol class="occ-pipeline">
                <li>
                    <span class="occ-pipeline-num">01</span>
                    <strong>Online application</strong>
                    <p>Fill IAF(TA)-9 (Revised) Part-1 online during the window. Choose your centre carefully — change requests are not entertained.</p>
                </li>
                <li>
                    <span class="occ-pipeline-num">02</span>
                    <strong>Admit card</strong>
                    <p>Released 10–15 days before the CBT. Carries your photo, signature and centre address. SMS and email alerts are sent in parallel.</p>
                </li>
                <li>
                    <span class="occ-pipeline-num">03</span>
                    <strong>Computer Based Test (CBT)</strong>
                    <p>PAN-India exam on 12 July 2026. The Territorial Army Directorate reserves the right to use any city depending on applicant volume.</p>
                </li>
                <li>
                    <span class="occ-pipeline-num">04</span>
                    <strong>Service Selection Board</strong>
                    <p>Successful candidates are called for a two-stage SSB. Stage 1 is a screening on day one; only those who clear it continue to stage 2.</p>
                </li>
                <li>
                    <span class="occ-pipeline-num">05</span>
                    <strong>Final merit</strong>
                    <p>Male and female candidates are placed in separate order of merit based on total marks (CBT + SSB).</p>
                </li>
            </ol>
        </section>

        <section class="occ-block">
            <p class="eyebrow">GROUP HEADQUARTERS</p>
            <h2>TA Group Headquarters by zone</h2>
            <div class="occ-hq-grid">
                <article>
                    <span class="occ-hq-zone">Zone 1 · Western</span>
                    <p>Building No.750, Sector 8-B, Chandigarh — 160018</p>
                    <small>Ph 0172-2547864</small>
                </article>
                <article>
                    <span class="occ-hq-zone">Zone 2 · Central</span>
                    <p>Lucknow (UP) — 226002</p>
                    <small>Ph 0522-2482278 · 8953401641</small>
                </article>
                <article>
                    <span class="occ-hq-zone">Zone 3 · Eastern</span>
                    <p>Fort William, Kolkata (WB) — 700021</p>
                    <small>Ph 033-22313227</small>
                </article>
                <article>
                    <span class="occ-hq-zone">Zone 4 · Southern</span>
                    <p>Pune — 411001</p>
                    <small>Ph 7768002503 · 020-268812</small>
                </article>
                <article>
                    <span class="occ-hq-zone">Zone 5 · Northern</span>
                    <p>Udhampur (J&amp;K) — 182101</p>
                    <small>Ph 01992-243592</small>
                </article>
            </div>
        </section>

        <section class="occ-block">
            <p class="eyebrow">DOCUMENTS AT SSB</p>
            <h2>What to carry on interview day</h2>
            <p>Bring every item in <strong>original</strong> along with self-attested copies. The checklist is enforced strictly.</p>
            <ol class="occ-docs">
                <li>Application Form IAF (TA)-9 (Revised) Part-2 — downloaded from the portal and filled in your own hand.</li>
                <li>All educational qualification certificates — Matric onwards.</li>
                <li>Identity proof with photograph — Voter ID, PAN, Passport, Driving licence or Aadhaar.</li>
                <li>Proof of residential address.</li>
                <li>Date-of-birth proof — Matric / Senior Secondary mark sheet &amp; certificate.</li>
                <li>Gazette notification of India / State, or an affidavit supported by newspaper cuttings, if your name varies across documents.</li>
                <li>Latest income proof — Income Tax Revenue Department, Magistrate or Employer.</li>
                <li>Copy of your most recent Income Tax Return (ITR).</li>
                <li>Admit Card.</li>
                <li><strong>Self-employed candidates</strong> — Affidavit on non-judicial stamp paper stating nature of employment and annual income, with a PAN-card copy and self-certified character certificate.</li>
                <li><strong>Government / PSU candidates</strong> — Service certificate authenticated by Head of Office along with salary certificate and department-issued NOC.</li>
            </ol>
        </section>

        <section class="occ-block">
            <p class="eyebrow">PAY &amp; ALLOWANCES</p>
            <h2>Pay scales (VIIth CPC)</h2>
            <div class="occ-table-wrap">
                <table class="occ-pay">
                    <thead>
                        <tr><th>Rank</th><th>Level</th><th>Pay matrix</th><th>Military Service Pay</th></tr>
                    </thead>
                    <tbody>
                        <tr><td>Lieutenant</td><td>Level 10</td><td>56,100 – 1,77,500</td><td>₹ 15,500</td></tr>
                        <tr><td>Captain</td><td>Level 10A</td><td>61,300 – 1,93,900</td><td>₹ 15,500</td></tr>
                        <tr><td>Major</td><td>Level 11</td><td>69,400 – 2,07,200</td><td>₹ 15,500</td></tr>
                        <tr><td>Lt Colonel</td><td>Level 12A</td><td>1,21,200 – 2,12,400</td><td>₹ 15,500</td></tr>
                        <tr><td>Colonel</td><td>Level 13</td><td>1,30,600 – 2,15,900</td><td>₹ 15,500</td></tr>
                        <tr><td>Brigadier</td><td>Level 13A</td><td>1,39,600 – 2,17,600</td><td>₹ 15,500</td></tr>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="occ-block">
            <p class="eyebrow">COMMITMENT</p>
            <h2>Terms of service in brief</h2>
            <ul class="occ-terms">
                <li>Territorial Army is a part-time concept — <strong>two months training per year</strong>, not a full-time career.</li>
                <li>Serving in the TA does not guarantee pension; pension depends on embodied service.</li>
                <li>Commission is granted in the rank of <strong>Lieutenant</strong>.</li>
                <li>Pay, allowances and privileges match Regular Army Officers when embodied for training or military service.</li>
                <li>Promotions up to Lt Colonel are by <strong>time-scale</strong>, subject to criteria. Colonel and Brigadier are by selection.</li>
                <li>Officers commissioned to Infantry (TA) may be called out for longer military service depending on the requirement.</li>
            </ul>
        </section>

        <section class="occ-cta">
            <div>
                <p class="eyebrow">NEXT STEP</p>
                <h3>Register for result notifications</h3>
                <p>Want an SMS when the TA Office Written Examination result for this cycle is published? Register your roll number.</p>
            </div>
            <a href="{{ route('result.form') }}" class="button">Register for result notifications <span aria-hidden="true">→</span></a>
        </section>
    </section>
@endsection
