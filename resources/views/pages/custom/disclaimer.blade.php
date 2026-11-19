@extends('layouts.public')

@section('content')
    @include('partials.legal-doc', [
        'heroTitle' => 'Disclaimer',
        'heroLede'  => 'How we provide information on this website and the limits of our responsibility.',
        'kicker'    => 'IN THIS NOTICE',
        'updatedAt' => 'November 2026',
        'sections'  => [
            [
                'id' => 'purpose',
                'title' => 'Purpose of this site',
                'body' => "The information contained in this website, **www.jointerritorialarmy.com**, is for general information and exam purposes only. The site has been created for providing books for students who are aspiring to appear in Territorial Army officer entry exams in India.",
            ],
            [
                'id' => 'external-links',
                'title' => 'Links to external sites',
                'body' => "Through this website you are able to link to other websites which are not under the control of **www.jointerritorialarmy.com**. We have no control over the nature, content and availability of those sites. The inclusion of any links does not necessarily imply a recommendation or endorse the views expressed within them.",
            ],
            [
                'id' => 'no-warranty',
                'title' => 'No warranty of accuracy',
                'body' => "The information is provided by **www.jointerritorialarmy.com**. While we endeavour to keep the information up to date and correct, we make no representations or warranties of any kind, express or implied, about the completeness, accuracy, reliability, suitability or availability of the website, its content, products, services, or related graphics for any purpose. Any reliance you place on such information is therefore strictly at your own risk.",
            ],
            [
                'id' => 'availability',
                'title' => 'Site availability',
                'body' => "Every effort is made to keep the website up and running smoothly. However, **www.jointerritorialarmy.com** takes no responsibility for, and will not be liable for, the website being temporarily unavailable due to technical issues beyond our control.",
            ],
        ],
    ])
@endsection
