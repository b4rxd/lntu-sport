<?php

namespace App\Http\Controllers\Welcome;

use App\Http\Controllers\Controller;
use App\Models\Location;

class WelcomeController extends Controller
{
    public function index()
    {
        $locations = Location::query()
            ->where('enabled', true)
            ->with('regularSchedulers')
            ->orderBy('title')
            ->get();

        return view('welcome', compact('locations'));
    }
}
