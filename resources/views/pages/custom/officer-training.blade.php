@extends('layouts.public')

@section('content')
    <div class="pagehead">
        <div class="frame">
            <div class="crumb"><a href="{{ route('home') }}">Home</a> / Join as an Officer / Training</div>
            <h1>Officer Training</h1>
            <p>Six months of Pre-Commission Training per TA Rule 20A, plus the SRO 64 and SRO 65 amendments that govern pay, military training categories and commissioning.</p>
        </div>
    </div>

    <section class="frame occ">
        <section class="occ-hero">
            <div class="occ-hero-text">
                <p class="eyebrow">OVERVIEW</p>
                <h2>Pre-Commission Training — six months, not a day short</h2>
                <p>Every person appointed for a Territorial Army commission shall undergo <strong>Pre-Commission Training for up to 23 weeks</strong>, under the orders of the Director General (or Additional Director General) of Territorial Army, in a training institute specified by the Ministry of Defence.</p>
                <p><strong>Candidates are commissioned in the TA only on successful completion of the Pre-Commission Training.</strong> On completion, the officer is granted <strong>21 days</strong> of post-commissioning leave.</p>
            </div>
            <aside class="occ-dates">
                <h3>At a glance</h3>
                <dl>
                    <div><dt>Pre-Commission</dt><dd>Up to 23 weeks</dd></div>
                    <div><dt>Post-commission leave</dt><dd>21 days</dd></div>
                    <div><dt>Annual training</dt><dd>2 months per year</dd></div>
                    <div><dt>Governing rule</dt><dd>TA Rule 20A (amended 29 Apr 2023)</dd></div>
                </dl>
            </aside>
        </section>

        <section class="occ-block">
            <p class="eyebrow">MILITARY TRAINING CATEGORIES</p>
            <h2>What TA training consists of</h2>
            <p>Per the amended Rule 18, military training for a Territorial Army unit consists of:</p>
            <div class="occ-cards">
                <article><strong>Recruit Training</strong><p>For enrolled personnel joining fresh.</p></article>
                <article><strong>Annual Training</strong><p>Yearly refresher for all TA personnel.</p></article>
                <article><strong>Pre-Commission Training</strong><p>Mandatory before commissioning as officer.</p></article>
                <article><strong>Post-Commission Training</strong><p>Given only for Departmental TA officers.</p></article>
                <article><strong>Voluntary Training</strong><p>Over and above the mandatory schedule.</p></article>
                <article><strong>Permanent Staff Service</strong><p>Full-time attachment to unit administration.</p></article>
            </div>
        </section>

        <section class="occ-block">
            <p class="eyebrow">SRO 64 · PAY</p>
            <h2>Counting of service for increments of pay</h2>
            <p><em>MoD Notification · New Delhi · 29 April 2023</em></p>
            <blockquote>
                25.(a) <strong>Counting of Service for Increments of Pay —</strong> For purpose of increments of pay, officers will be permitted to count all commissioned service in the Territorial Army under Sec 7(3)(a)(b) &amp; (c) of the Act. <strong>The training period during the Pre-Commissioning Training, however, shall not be counted for the said purpose</strong> in terms of the training, as given in Sec 7(3).
            </blockquote>
        </section>

        <section class="occ-block">
            <p class="eyebrow">SRO 65 · RULE 20A</p>
            <h2>Pre-Commission Training — amended rules</h2>
            <ol class="occ-pipeline">
                <li>
                    <span class="occ-pipeline-num">01</span>
                    <strong>Duration</strong>
                    <p>Every person appointed shall undergo Pre-Commission Training for a total period <strong>not exceeding twenty-three weeks</strong> at a specified training institute.</p>
                </li>
                <li>
                    <span class="occ-pipeline-num">02</span>
                    <strong>Commissioning conditional</strong>
                    <p>A candidate shall be commissioned in the TA <strong>only on successful completion</strong> of the Pre-Commission Training.</p>
                </li>
                <li>
                    <span class="occ-pipeline-num">03</span>
                    <strong>Treatment during training</strong>
                    <p>During training, a person is treated at par with a gentleman / gentlewoman cadet of the institute — for all purposes, including <strong>twenty-one days of post-commissioning leave</strong>.</p>
                </li>
                <li>
                    <span class="occ-pipeline-num">04</span>
                    <strong>Exemption for Ex-Service officers</strong>
                    <p>Ex-officers commissioned through the Army Headquarters Selection Board (ASB) <strong>may be exempted</strong> from undergoing Pre-Commission Training, if, in the opinion of the DG (or Additional DG) of TA, they have undergone adequate military training in their previous Armed Forces service.</p>
                </li>
            </ol>
        </section>

        <section class="occ-block">
            <p class="eyebrow">AFTER TRAINING</p>
            <h2>Annual commitment</h2>
            <ul class="occ-terms">
                <li>Two months of annual training camp every year.</li>
                <li>Officers commissioned in Infantry (TA) may be called out for longer military service depending on operational requirement.</li>
                <li>Pay, allowances and privileges match Regular Army officers when embodied for training or military service.</li>
                <li>Ex-officers granted Ante-Date Seniority once previous service is verified from PCDA(O).</li>
            </ul>
        </section>
    </section>
@endsection
