<?php

namespace App\Http\Controllers\Web\About;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class AboutCompanyController extends Controller
{
    public function __invoke()
    {
        return Inertia::render('about/AboutCompanyPage');
    }
}
