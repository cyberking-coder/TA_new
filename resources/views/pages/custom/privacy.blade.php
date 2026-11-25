@extends('layouts.public', [
    'title'       => 'Privacy · Territorial Army Field Guide',
    'description' => 'Territorial Army Field Guide — Privacy',
])

@section('content')
    @include('partials.legal-doc', [
        'heroTitle' => 'Privacy Policy',
        'heroLede'  => 'How we collect, use and protect the information you share with us.',
        'kicker'    => 'IN THIS POLICY',
        'updatedAt' => 'November 2026',
        'sections'  => [
            [
                'id' => 'commitment',
                'title' => '1. Our commitment',
                'body' => "Your privacy is very important to us. We do not publish, sell or rent your personal information to third parties for their marketing purposes without your explicit consent.\n\nAt territorial army, we are extremely proud of our commitment to protect your privacy. We value your trust in us. We will work hard to earn your confidence so that you can enthusiastically use our services and recommend us to friends and family.",
            ],
            [
                'id' => 'guarantee',
                'title' => '2. Our guarantee',
                'body' => "We promise that we will not sell or rent your personal information to third parties for their marketing purposes without your explicit consent.\n\nFrom time to time we may reveal general statistical information about our website and visitors — such as number of visitors, number and type of goods and services purchased. Your trust and confidence are our highest priority.",
            ],
            [
                'id' => 'collect',
                'title' => '3. Information we collect',
                'body' => "When you use our site, we collect and store your personal information from you. Our primary goal is to provide a safe, efficient, smooth and customized experience. We only collect personal information about you that we consider necessary for achieving this purpose.\n\nYou can browse the Site without telling us who you are or revealing any personal information about yourself. To fully use the Site you will need to register, which may require your name, date of birth, contact number, email id, password, residence or place of business, billing information, shipping information, bank account details and other personal information as indicated on the forms.\n\nWe automatically track certain information about you based on your behaviour on the site — URLs you came from, your computer browser and your IP address. This is used in aggregate to improve our service.\n\nWe use cookies on certain pages to help analyze flow, measure promotional effectiveness and promote trust and safety. Most cookies are session cookies, deleted when your session ends. You can decline cookies via your browser; some features may be unavailable in that case.",
            ],
            [
                'id' => 'use',
                'title' => '4. How we use your information',
                'body' => "We use your personal information to facilitate the services you request: resolve disputes, troubleshoot problems, promote safe trading, collect fees owed, measure interest in our services, inform you about online and offline offers and updates, customize your experience, detect and protect against error, fraud and other criminal activity, enforce our User Agreement, and as otherwise described to you at the time of collection.\n\nYou agree that we may use personal information about you to improve our marketing efforts, to analyze site usage, improve content and product offerings, and customize layout and services.",
            ],
            [
                'id' => 'disclosure',
                'title' => '5. When we disclose information',
                'body' => "We may share your information with trusted business partners who help us deliver services — payment processors, delivery partners, SMS and email service providers — all bound by strict confidentiality obligations. We will also disclose your personal information if required by law, to enforce our User Agreement, or to protect our rights, property or safety.",
            ],
            [
                'id' => 'contact',
                'title' => '6. Contact us',
                'body' => "For questions about this policy email **taexambook@gmail.com** or WhatsApp **9017516001**.",
            ],
        ],
    ])
@endsection
