@extends('layouts.public')

@section('content')
    <div class="pagehead">
        <div class="frame">
            <div class="crumb"><a href="{{ route('home') }}">Home</a> / Join as a Jawan / JCO</div>
            <h1>Join as a Jawan / JCO</h1>
            <p>Recruitment into Infantry (TA), Ecological (TA) and Home &amp; Hearth battalions — selection is zonal, through recruiting offices and rallies.</p>
        </div>
    </div>

    <section class="frame occ">
        <section class="occ-hero">
            <div class="occ-hero-text">
                <p class="eyebrow">OVERVIEW</p>
                <h2>Report to the Recruiting Office on the advertised date</h2>
                <p>Applicants report to the unit or recruiting office on designated dates advertised in the newspaper and on the Indian Army website. Selected candidates go through the Medical Board and Police Verification.</p>
                <p><strong>Recruitment is on a Zonal basis</strong> — your home state determines the zone you apply from.</p>
            </div>
            <aside class="occ-dates">
                <h3>Eligibility</h3>
                <dl>
                    <div><dt>Age</dt><dd>18 to 42 years</dd></div>
                    <div><dt>Education</dt><dd>Matriculation (dispensation possible in specified cases)</dd></div>
                    <div><dt>Mode</dt><dd>Advertised rally / walk-in</dd></div>
                </dl>
                <a class="button" href="https://joinindianarmy.nic.in/latest-rally-jcos-or.htm" target="_blank" rel="noopener">Jawan recruitment schedule <span aria-hidden="true">→</span></a>
            </aside>
        </section>

        <section class="occ-block">
            <p class="eyebrow">RECRUITMENT ZONES</p>
            <h2>Which zone do you apply from?</h2>
            <div class="occ-cards">
                <article>
                    <strong>Zone I</strong>
                    <p>Haryana, Himachal Pradesh, Jammu &amp; Kashmir, Punjab and the Union Territories of Delhi &amp; Chandigarh.</p>
                </article>
                <article>
                    <strong>Zone II</strong>
                    <p>Bihar, Madhya Pradesh, Odisha, Uttar Pradesh, Uttarakhand, Jharkhand and Chhattisgarh.</p>
                </article>
                <article>
                    <strong>Zone III</strong>
                    <p>Assam, Meghalaya, Manipur, Tripura, Nagaland, Sikkim, West Bengal, Mizoram, Arunachal Pradesh and Andaman &amp; Nicobar Islands.</p>
                </article>
                <article>
                    <strong>Zone IV</strong>
                    <p>Andhra Pradesh, Gujarat, Kerala, Tamil Nadu, Maharashtra, Rajasthan, Karnataka and the UTs of Dadra &amp; Nagar Haveli, Goa, Daman &amp; Diu, Lakshadweep and Pondicherry.</p>
                </article>
            </div>
        </section>

        <section class="occ-block">
            <p class="eyebrow">LATEST OPENINGS</p>
            <h2>Recent recruitment advertisements</h2>
            <ol class="occ-docs">
                <li>Advertisement for recruitment in Infantry Battalion (TA) — units under <strong>Territorial Army Group Headquarters, Southern Command</strong>.</li>
                <li>Recruitment Rally in <strong>137 Composite Ecological Task Force Battalion (TA) 39 Gorkha Rifles</strong>.</li>
                <li>Advertisement for recruitment in Infantry Battalion (TA).</li>
                <li>Advertisement for recruitment in Infantry Battalion (TA) — units Central Command.</li>
                <li>Advertisement for recruitment in Infantry Battalions / <strong>Home &amp; Hearth Battalions (TA)</strong>.</li>
                <li>TA Group HQ, Northern Command advertisement — Infantry / Home &amp; Hearth / Ikhwan / Engineer Regiment.</li>
                <li><strong>Bihar &amp; Uttar Pradesh</strong> Territorial Army recruitment rally.</li>
            </ol>
        </section>

        <section class="occ-block">
            <p class="eyebrow">GROUP HEADQUARTERS</p>
            <h2>TA Group Headquarters by command</h2>
            <div class="occ-hq-grid">
                <article>
                    <span class="occ-hq-zone">Western Command</span>
                    <p>Building No 750, Sector 8-B, Chandigarh (UT)</p>
                    <small>Tele: 0172-2781957 · 0172-2781859</small>
                </article>
                <article>
                    <span class="occ-hq-zone">Central Command</span>
                    <p>Lucknow</p>
                    <small>Tele: 0522-2292245 · 0522-2292250</small>
                </article>
                <article>
                    <span class="occ-hq-zone">Eastern Command</span>
                    <p>Fort William, Kolkata</p>
                    <small>Tele: 033-22313227</small>
                </article>
                <article>
                    <span class="occ-hq-zone">Southern Command</span>
                    <p>Pune</p>
                    <small>Tele: 020-2680123</small>
                </article>
                <article>
                    <span class="occ-hq-zone">Northern Command</span>
                    <p>Udhampur</p>
                    <small>Tele: 01992-243592</small>
                </article>
            </div>
        </section>

        <section class="occ-block">
            <p class="eyebrow">DG TA · JCO ENQUIRIES</p>
            <h2>Directorate General Territorial Army</h2>
            <div class="occ-hq-grid">
                <article>
                    <span class="occ-hq-zone">GS Branch</span>
                    <p>L Block, Church Road, New Delhi — 01</p>
                    <small>JCO &amp; Other Ranks queries: 011-23092827</small>
                </article>
            </div>
        </section>
    </section>
@endsection
