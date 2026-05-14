<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function __invoke()
    {
        $brands = Brand::get();

        return Inertia::render('HomePage', compact(['brands']));
    }
}
