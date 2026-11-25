@extends('layouts.public', [
    'title'       => 'For Ex-Service Officer · Territorial Army Field Guide',
    'description' => 'A route for gainfully employed Ex Armed Forces Commissioned Officers to return to the uniform as Territorial Army Officers.',
])

@section('content')
    <div class="pagehead">
        <div class="frame">
            <div class="crumb"><a href="{{ route('home') }}">Home</a> / Join as an Officer / For Ex-Service Officer</div>
            <h1>For Ex-Service Officer</h1>
            <p>A route for gainfully employed Ex Armed Forces Commissioned Officers to return to the uniform as Territorial Army Officers.</p>
        </div>
    </div>

    <section class="frame occ">
        <section class="occ-hero">
            <div class="occ-hero-text">
                <p class="eyebrow">ABOUT THIS ROUTE</p>
                <h2>Return to service without giving up your civilian life</h2>
                <p>Applications are invited from gainfully employed <strong>Ex Armed Forces Commissioned Officers</strong> for the opportunity of donning the uniform and serving the nation as Territorial Army Officers.</p>
                <p>You serve the nation in two capacities — as a civilian and as a soldier — without having to sacrifice your primary profession.</p>
                <p class="occ-warn"><strong>Note:</strong> Only Ex Service Commissioned Officers can apply. Serving members of the Regular Army, Navy, Air Force, Police, GREF, Para-Military and like forces are <strong>not eligible</strong>.</p>
            </div>

            <aside class="occ-dates">
                <h3>Key dates &amp; vacancies</h3>
                <dl>
                    <div>
                        <dt>Application window</dt>
                        <dd>27 July — 25 August 2026</dd>
                    </div>
                    <div>
                        <dt>Interview</dt>
                        <dd>September 2026 (firm dates by post / email)</dd>
                    </div>
                    <div>
                        <dt>Mode of exam</dt>
                        <dd>Only interview (no written test)</dd>
                    </div>
                    <div>
                        <dt>Vacancies</dt>
                        <dd>Male 04 &middot; Female 01</dd>
                    </div>
                </dl>
                <a class="button" href="https://www.jointerritorialarmy.gov.in" target="_blank" rel="noopener">Download IAF (TA)-9 Part-1 <span aria-hidden="true">→</span></a>
            </aside>
        </section>

        <section class="occ-block">
            <p class="eyebrow">ELIGIBILITY</p>
            <h2>Who can apply</h2>
            <div class="occ-cards">
                <article>
                    <strong>Service</strong>
                    <p>Only Ex Service Commissioned Officers of the Indian Armed Forces.</p>
                </article>
                <article>
                    <strong>Nationality</strong>
                    <p>Only citizens of India — male and female.</p>
                </article>
                <article>
                    <strong>Age</strong>
                    <p>18 to 42 years on the date of application.</p>
                </article>
                <article>
                    <strong>Education</strong>
                    <p>Graduate from a recognized university.</p>
                </article>
                <article>
                    <strong>Physical standards</strong>
                    <p>Physically and medically fit in all respects.</p>
                </article>
                <article>
                    <strong>Employment</strong>
                    <p>Must be gainfully employed at the time of application.</p>
                </article>
            </div>
        </section>

        <section class="occ-block">
            <p class="eyebrow">HOW TO APPLY</p>
            <h2>Submission pipeline</h2>
            <ol class="occ-pipeline">
                <li>
                    <span class="occ-pipeline-num">01</span>
                    <strong>Download the form</strong>
                    <p>Download Application Form <strong>IAF (TA)-9 (Revised) Part-1</strong> from jointerritorialarmy.gov.in (free of cost).</p>
                </li>
                <li>
                    <span class="occ-pipeline-num">02</span>
                    <strong>Attach documents</strong>
                    <p>Send photocopies of your <strong>Release Order</strong> and <strong>Release Medical Board Proceedings</strong> along with the completed form.</p>
                </li>
                <li>
                    <span class="occ-pipeline-num">03</span>
                    <strong>Send by India Post only</strong>
                    <p>Despatch to: Directorate General Territorial Army (TA-4), Integrated HQ of Ministry of Defence, 4th Floor 'A' Block, KG Marg, New Delhi — 110001. <strong>Courier / hand delivery is not accepted.</strong></p>
                </li>
                <li>
                    <span class="occ-pipeline-num">04</span>
                    <strong>Army HQ Selection Board (ASB)</strong>
                    <p>Screened by the Army Headquarters Selection Board at Additional Directorate General Territorial Army, New Delhi.</p>
                </li>
                <li>
                    <span class="occ-pipeline-num">05</span>
                    <strong>Medical &amp; police verification</strong>
                    <p>Recommended officers undergo a medical examination at the Armed Forces Clinic, New Delhi, followed by police verification for final selection.</p>
                </li>
            </ol>
        </section>

        <section class="occ-block">
            <p class="eyebrow">POINT OF CONTACT</p>
            <h2>Directorate General Territorial Army</h2>
            <div class="occ-hq-grid">
                <article>
                    <span class="occ-hq-zone">DG TA · TA-4</span>
                    <p>Integrated HQ of Ministry of Defence, 4th Floor 'A' Block, KG Marg, New Delhi — 110001</p>
                    <small>Territorial Army Officer: 011-21411121</small>
                </article>
            </div>
        </section>

        <section class="occ-block">
            <p class="eyebrow">DOCUMENTS AT INTERVIEW</p>
            <h2>What to carry on interview day</h2>
            <p>Bring every item in <strong>original</strong> along with self-attested copies. Candidates whose complete documents are not produced for verification will not be allowed to attend the interview.</p>
            <ol class="occ-docs">
                <li>Application Form IAF (TA)-9 (Revised) Part-2 — downloaded and filled in your own hand.</li>
                <li>All educational qualification certificates — Matric onwards.</li>
                <li>Latest physical fitness certificate from a registered MBBS doctor.</li>
                <li>Identity proof with photograph — Voter ID, PAN, Aadhaar, Passport or Driving licence.</li>
                <li>Domicile / Residential proof.</li>
                <li>Date-of-birth proof — Matric / Senior Secondary mark sheet &amp; certificate.</li>
                <li><strong>Government / PSU candidates</strong> — Service certificate + No Objection Certificate by the department, authenticated by Head of Office.</li>
                <li><strong>Private sector candidates</strong> — Service certificate authenticated by Head of Office + No Objection Certificate.</li>
                <li><strong>Self-employed candidates</strong> — Affidavit on non-judicial stamp paper stating nature of employment and annual income + PAN-card copy + self-certified character certificate.</li>
                <li>Gazette notification of India / State, duly supported by newspaper cuttings, if your name varies across documents.</li>
                <li>Copy of your Release Order.</li>
                <li>Copy of your Release Medical Board Proceedings.</li>
                <li>Latest income proof — Income Tax Revenue Department, Magistrate or Employer.</li>
                <li>Copy of PAN card.</li>
                <li>Copy of Aadhaar card.</li>
                <li>Certificates and reports of all courses attended.</li>
                <li>Discharge / Service Particular Booklet.</li>
                <li>Copy of most recent Income Tax Return (ITR).</li>
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
            <p class="fine" style="margin-top:10px;color:var(--muted);font-size:13px">Once previous service is verified from the concerned PCDA(O), Ex-Service Officers are granted <strong>Ante-Date Seniority</strong> for pay and promotion.</p>
        </section>

        <section class="occ-block">
            <p class="eyebrow">COMMITMENT</p>
            <h2>Terms of service in brief</h2>
            <ul class="occ-terms">
                <li>Territorial Army is a part-time concept — <strong>two months training per year</strong>, not a full-time career.</li>
                <li>Serving in the TA does not guarantee pension; pension depends on embodied service.</li>
                <li>Commission is granted in the rank of <strong>Lieutenant</strong>, subject to requirements under TA Rule 20A.</li>
                <li>Pay, allowances and privileges match Regular Army Officers when embodied for training or military service.</li>
                <li>Promotions up to Lt Colonel are by <strong>time-scale</strong>, subject to criteria. Colonel and Brigadier by selection.</li>
                <li>Officers commissioned to Infantry (TA) may be called out for longer military service depending on the requirement.</li>
            </ul>
        </section>

        <section class="occ-cta">
            <div>
                <p class="eyebrow">NEXT STEP</p>
                <h3>Register for result notifications</h3>
                <p>Get an SMS the moment the result for this cycle is published. Enter your examination name and roll number.</p>
            </div>
            <a href="{{ route('result.form') }}" class="button">Register for result notifications <span aria-hidden="true">→</span></a>
        </section>
    </section>
@endsection
