<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class Phase3ExclusionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_ships_no_routes_for_the_excluded_modules(): void
    {
        $routes = collect(Route::getRoutes())->map(fn ($r) => $r->uri())->all();
        foreach (['exam-centres', 'exam_centres', 'coupons', 'coupon', 'consignments', 'consignment-csv', 'move-to-officers'] as $banned) {
            foreach ($routes as $uri) {
                $this->assertFalse(str_contains($uri, $banned), "Route {$uri} references excluded module: {$banned}");
            }
        }
    }

    public function test_has_no_tables_for_the_excluded_modules(): void
    {
        foreach (['exam_centres', 'coupons', 'consignments'] as $banned) {
            $this->assertFalse(Schema::hasTable($banned), "Table {$banned} must not exist");
        }
    }
}
