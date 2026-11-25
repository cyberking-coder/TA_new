@extends('layouts.public', [
    'title'       => 'Instructions For SSB Interview · Territorial Army Field Guide',
    'description' => 'Reporting, documents to carry, dress code, discipline — a practical checklist for candidates attending the Service Selection Board.',
])

@section('content')
    <div class="pagehead">
        <div class="frame">
            <div class="crumb"><a href="{{ route('home') }}">Home</a> / SSB / Instructions</div>
            <h1>Instructions For SSB Interview</h1>
            <p>Reporting, documents to carry, dress code, discipline — a practical checklist for candidates attending the Service Selection Board.</p>
        </div>
    </div>

    <section class="frame occ">
        <section class="occ-hero">
            <div class="occ-hero-text">
                <p class="eyebrow">OVERVIEW</p>
                <h2>Prepare for five days of assessment</h2>
                <p>Report on the date and time printed on your call letter. Late reporting is at your own risk and may lead to not being tested. Be honest — the Personal Information Questionnaire and interview are read carefully by trained assessors; fabrication is caught quickly and results in rejection.</p>
            </div>
            <aside class="occ-dates">
                <h3>Essentials</h3>
                <dl>
                    <div><dt>Duration</dt><dd>5 days</dd></div>
                    <div><dt>Dress</dt><dd>Formal + PT kit</dd></div>
                    <div><dt>Mobile phones</dt><dd>Banned in testing area</dd></div>
                    <div><dt>Travel fare</dt><dd>SC class reimbursed by shortest route</dd></div>
                </dl>
            </aside>
        </section>

        <section class="occ-block">
            <p class="eyebrow">DOCUMENTS</p>
            <h2>What to carry — original + self-attested copies</h2>
            <ol class="occ-docs">
                <li>10th and 12th mark sheets</li>
                <li>Graduation degree</li>
                <li>Caste / category certificate, if applicable</li>
                <li>Domicile certificate</li>
                <li>Aadhaar and PAN</li>
                <li>Latest photographs — <strong>at least 24 copies</strong></li>
                <li>Admit Card / call letter</li>
                <li>Employment certificate, where applicable</li>
                <li>Income Tax Return and proof of income</li>
                <li>Filled-in <strong>Personal Information Questionnaire (PIQ)</strong> form</li>
            </ol>
        </section>

        <section class="occ-block">
            <p class="eyebrow">KIT LIST</p>
            <h2>What to pack</h2>
            <div class="occ-cards">
                <article>
                    <strong>Formal attire</strong>
                    <p>Plain white shirts, plain white vest, light-coloured trousers. Add tie for men; salwar-kameez or formal pant-shirt for women.</p>
                </article>
                <article>
                    <strong>PT kit</strong>
                    <p>Shorts, T-shirt, sports shoes. Needed for GTO and individual obstacle events.</p>
                </article>
                <article>
                    <strong>Everyday</strong>
                    <p>Formal shoes, slippers, towel, undergarments for 5 days, shaving kit, pen, pencil, eraser, notebook. Keep baggage minimum.</p>
                </article>
            </div>
        </section>

        <section class="occ-block">
            <p class="eyebrow">CONDUCT</p>
            <h2>Rules at the Selection Centre</h2>
            <div class="syl-rules">
                <article class="syl-rule">
                    <h3>Dress and grooming</h3>
                    <p>Formal attire for interviews. Hair combed, clean-shaven — or neatly-trimmed beard where religion requires. Shoes polished. Address officers as "Sir / Ma'am".</p>
                </article>
                <article class="syl-rule syl-rule--warn">
                    <h3>Attendance is mandatory</h3>
                    <p>Attendance at all tests and briefings is compulsory. Follow fall-in timings. Any absence must be cleared through the Call-Up Office or Administrative Office.</p>
                </article>
                <article class="syl-rule syl-rule--danger">
                    <h3>Mobile phones banned</h3>
                    <p>Mobile phones are <strong>banned</strong> inside the testing area. Switch off and deposit where instructed. Any attempt to carry or use one leads to immediate disqualification.</p>
                </article>
                <article class="syl-rule">
                    <h3>During tests</h3>
                    <p>Speak clearly, do not interrupt others, back your opinions with reasoning, and remain open to others' views. In GTO tasks, lead with intent but stay a team player.</p>
                </article>
                <article class="syl-rule">
                    <h3>Declare pre-existing conditions</h3>
                    <p>Recommended candidates are put through a medical board. Arrive rested and well-hydrated. Declare any pre-existing conditions truthfully.</p>
                </article>
            </div>
        </section>

        <section class="occ-block">
            <p class="eyebrow">LOGISTICS</p>
            <h2>Travel &amp; help desk</h2>
            <div class="occ-cards">
                <article>
                    <strong>Travel reimbursement</strong>
                    <p>First-time SC class train fare is reimbursed by the shortest route. Carry original ticket or proof of travel.</p>
                </article>
                <article>
                    <strong>Help desk</strong>
                    <p>For queries at the Selection Centre, visit the <strong>Call-Up Office</strong> or the Administrative Office of the Board hosting you.</p>
                </article>
                <article>
                    <strong>Honesty</strong>
                    <p>Be honest in the PIQ and interview. Fabrication is noticed quickly by trained assessors and results in rejection regardless of other performance.</p>
                </article>
            </div>
        </section>
    </section>
@endsection
