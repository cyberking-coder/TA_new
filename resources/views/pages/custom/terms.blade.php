@extends('layouts.public', [
    'title'       => 'Terms · Territorial Army Field Guide',
    'description' => 'Territorial Army Field Guide — Terms',
])

@section('content')
    @include('partials.legal-doc', [
        'heroTitle' => 'Terms &amp; Conditions',
        'heroLede'  => 'Rules for using this website and buying from the bookshop. Please read carefully.',
        'kicker'    => 'IN THESE TERMS',
        'updatedAt' => 'November 2026',
        'sections'  => [
            [
                'id' => 'use',
                'title' => '1. Terms of use',
                'body' => "We provide services to you subject to the notices, terms and conditions set forth in this agreement. In addition, when you use any of our services — customer reviews, book orders, study-access — you will be subject to the rules, guidelines, policies, terms and conditions applicable to such service.\n\nWe reserve the right to change this site and these terms and conditions at any time. We retain the right to deny access to anyone we believe to have violated any of these terms. By accessing, browsing or otherwise using the site you indicate your agreement to all the terms and conditions in this agreement.",
            ],
            [
                'id' => 'content',
                'title' => '2. Content posted on the site',
                'body' => "You are responsible for any notes, messages, emails, photos, drawings, profiles, opinions, ideas, images, videos, audio files or other materials posted or transmitted to the site. Such content will become our property throughout the universe, to be used consistent with our Privacy Policy and these terms, for any type of use forever including promotional and advertising purposes, without any pay or other compensation to you.",
            ],
            [
                'id' => 'conduct',
                'title' => '3. Rules of conduct on site',
                'body' => "You may not post, distribute, or reproduce any copyrighted material, trademarks, or proprietary information without obtaining the prior written consent of the owner. Doing so is an infringement of intellectual property rights.\n\nYou must use the site in a manner consistent with all applicable laws and regulations. Illegal and unauthorized uses — collecting usernames or email addresses by electronic means for sending unsolicited email, framing or linking without permission — are not permitted.",
            ],
            [
                'id' => 'links',
                'title' => '4. Links to third-party sites',
                'body' => "This site may contain links to other web sites or resources. Because we have no control over such sites, you acknowledge and agree that we are not responsible for their availability or content, and do not endorse or accept liability for anything on them.",
            ],
            [
                'id' => 'products',
                'title' => '5. Our products',
                'body' => "We offer products related to the Territorial Army Commission (TAC) examination — printed books and buyer-only study PDFs. Product descriptions are provided to help you make informed decisions. We do not warrant that descriptions or other content are accurate, complete, reliable, current or error-free. If a product is not as described, your sole remedy is to return it unused.",
            ],
            [
                'id' => 'payment',
                'title' => '6. Payment',
                'body' => "While availing any payment method offered, we are not responsible or liable for loss arising from decline due to: lack of authorization for a transaction, exceeding a mutually agreed preset limit, any payment issue arising out of the transaction, or decline for any other reason. All payments shall be in Indian Rupees acceptable by an Indian bank. We do not accept any other currency.",
            ],
            [
                'id' => 'law',
                'title' => '7. Applicable law',
                'body' => "This agreement shall be governed by and interpreted in accordance with the laws of India. The place of jurisdiction shall be Chandigarh.",
            ],
            [
                'id' => 'copyright',
                'title' => '8. Trademark, copyright &amp; restrictions',
                'body' => "All material on this site, including images, illustrations, audio clips and video clips, is protected by copyrights, trademarks and other intellectual property rights that are owned and controlled by us or by parties that have licensed their material to us. The material is solely for your personal, non-commercial use. You must not reproduce, republish, upload, post, transmit or distribute any such material in any way without our prior written consent.",
            ],
            [
                'id' => 'account',
                'title' => '9. Your account',
                'body' => "Any use of the site is at your own risk. You are responsible for maintaining the confidentiality of your account and password and for all activities that occur under your account. Products sold on our site are not for people below age 18.",
            ],
            [
                'id' => 'risk',
                'title' => '10. Risk of loss',
                'body' => "All items purchased are made pursuant to a shipment contract. Risk of loss and title for such items pass to you upon our delivery to the carrier.",
            ],
            [
                'id' => 'pricing',
                'title' => '11. Pricing &amp; typographical error',
                'body' => "While product listings try to be accurate, due to technical, typographical or other product information received from our suppliers, prices may vary. If you find a pricing discrepancy, contact us within 48 hours of placing your order. We will refund any difference within 10–15 business days via the same mode used for the order.",
            ],
            [
                'id' => 'cancellation',
                'title' => '12. Cancellation of order',
                'body' => "We reserve the right to cancel any order without explanation where we are unable to meet the requirement or where the order does not comply with our policy. Any cancellation will be communicated to you within appropriate time.",
            ],
            [
                'id' => 'liability',
                'title' => '13. Limitation of liability',
                'body' => "In no event shall we be liable for any special, incidental, indirect or consequential damages of any kind in connection with this agreement, even if we have been informed in advance of the possibility of such damages.",
            ],
            [
                'id' => 'contact',
                'title' => '14. Contact',
                'body' => "Please send any questions or comments, including inquiries unrelated to copyright infringement, regarding this site to **territorialarmyexambooks@gmail.com**.",
            ],
        ],
    ])
@endsection
