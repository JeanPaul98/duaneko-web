<?php

namespace App\Http\Controllers;

use App\Models\AdministrativeDivision;
use App\Models\Country;
use Spatie\Activitylog\Models\Activity;

class SettingsController extends Controller
{
    public function index()
    {
        $countriesCount = Country::count();
        $administrativeDivisionsCount = AdministrativeDivision::count();
        $activityCount = Activity::count();

        return view('pages.settings.index', compact('countriesCount', 'administrativeDivisionsCount', 'activityCount'));
    }
}
