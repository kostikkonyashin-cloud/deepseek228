<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\TeamMember;
use App\Models\WorkshopService;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function __invoke()
    {
        $brands = Brand::get();
        $members = TeamMember::get();
        $services = WorkshopService::get();

        return Inertia::render('HomePage', compact(['brands', 'members', 'services']));
    }
}
