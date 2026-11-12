<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

/**
 * Seeds the static legacy content pages into the Page model.
 * Content is placeholder but structured so staff can edit through
 * Filament -> Content -> Pages without needing a developer.
 */
class LegacyPagesSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            // Life in Army group
            ['life-in-army', 'Life in Army', 'Life in the Territorial Army — overview, what to expect and who it is for.'],
            ['what-army-offers-you', 'What the army offers you', 'Pay, allowances, honour and the professional experience of serving in the Territorial Army.'],
            ['service-benefits', 'Service benefits', 'Service benefits for TA personnel and their families.'],
            ['rules-and-regulations', 'Rules and regulations', 'Rules and regulations governing Territorial Army service.'],

            // TA Units group
            ['ta-units', 'TA Units', 'The unit types that make up the Territorial Army today.'],
            ['ecological-ta', 'Ecological TA', 'Ecological Territorial Army battalions: role, formation and recent operations.'],
            ['departmental-ta', 'Departmental TA', 'Departmental TA units: Railway, IOC, ONGC and other departmental contingents.'],

            // Standalone legacy pages
            ['ta-policies', 'TA policies', 'Official policies covering recruitment, service and retirement under the Territorial Army Act.'],
            ['ta-command-contacts', 'TA Command Contacts', 'Command headquarters and contact numbers across the country.'],
            ['join-as-jawan', 'Join as a Jawan / JCO', 'Eligibility, process, documents and recruitment zones for Jawan and JCO entries.'],
            ['join-as-officer', 'Join as an Officer', 'Eligibility, selection board and training for Territorial Army officer entries.'],
            ['about-us', 'About us', 'Who runs this website and how it relates to the Territorial Army.'],
            ['contact-us', 'Contact us', 'Contact information for the publisher of this website. Not an official TA contact.'],

            // Policies / footer group
            ['privacy', 'Privacy policy', 'How this website collects, uses and protects your personal information.'],
            ['terms', 'Terms & conditions', 'Terms that apply to visitors, buyers and subscribers of this website.'],
            ['disclaimer', 'Disclaimer', 'Independent publisher disclaimer and limits of liability.'],
            ['shipping-delivery', 'Shipping & delivery', 'How the book ships, delivery timelines and tracking details.'],
            ['refund-cancellation', 'Refund & cancellation policy', 'Refund, return and cancellation rules for the examination book.'],
        ];

        foreach ($pages as [$slug, $title, $summary]) {
            Page::updateOrCreate(
                ['slug' => $slug],
                [
                    'title' => $title,
                    'menu_label' => $title,
                    'seo_title' => $title,
                    'seo_description' => $summary,
                    'body' => $this->placeholderBody($title, $summary),
                    'status' => Page::STATUS_PUBLISHED,
                    'published_at' => now(),
                ],
            );
        }

        $this->command->info('Seeded '.count($pages).' legacy pages. Edit them at /admin/pages.');
    }

    private function placeholderBody(string $title, string $summary): string
    {
        return "<p><strong>{$summary}</strong></p>"
            ."<p>This page is a placeholder. Real content is maintained by staff in the admin panel "
            ."(<a href=\"/admin/pages\">Content → Pages</a>) and can be published or withdrawn without a code change.</p>"
            ."<p>For the authoritative version, check the official Territorial Army website.</p>";
    }
}
