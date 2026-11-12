<?php

namespace App\Http\Controllers;

class PlaceholderController extends Controller
{
    public function render(string $heading, string $blurb, string $phaseLabel, ?string $robots = null)
    {
        return view('static.placeholder', [
            'title' => $heading.' — Territorial Army exam guide',
            'description' => $blurb,
            'canonical' => url()->current(),
            'heading' => $heading,
            'blurb' => $blurb,
            'phaseLabel' => $phaseLabel,
            'robots' => $robots,
        ]);
    }
}
