<?php

namespace App\Services\Seo;

final class PageReport
{
    /** @param list<string> $errors */
    public function __construct(
        public readonly string $url,
        public readonly int $status,
        public readonly int $hops,
        public readonly string $title,
        public readonly string $description,
        public readonly int $h1Count,
        public readonly string $canonical,
        public readonly int $jsonLdCount,
        public readonly int $imagesTotal,
        public readonly int $imagesMissingAlt,
        public readonly array $errors,
    ) {}
}
